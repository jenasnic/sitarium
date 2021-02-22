<?php

namespace App\Service\Maze;

use App\Entity\Maze\Actor;
use App\Entity\Maze\FilmographyMovie;
use App\Enum\Maze\FilmographyStatusEnum;
use App\Event\MazeEvents;
use App\Event\Maze\FilmographyProgressEvent;
use App\Event\Maze\FilmographyStartEvent;
use App\Model\Tmdb\Movie;
use App\Repository\Maze\ActorRepository;
use App\Repository\Maze\FilmographyMovieRepository;
use App\Service\Tmdb\TmdbApiService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use App\Service\Converter\FilmographyMovieConverter;

/**
 * This class allows to get all movies relative to existing actors and to build filmography keeping only movies linked together
 * (i.e. a movie with only one actor won't be kept and saved in database).
 */
class ActorFilmographyBuilder
{
    /**
     * @var TmdbApiService
     */
    protected $tmdbApiService;

    /**
     * @var FilmographyMovieConverter
     */
    protected $filmographyMovieConverter;

    /**
     * @var ActorRepository
     */
    protected $actorRepository;

    /**
     * @var FilmographyMovieRepository
     */
    protected $filmographyMovieRepository;

    /**
     * @var EntityManagerInterface
     */
    protected $entityManager;

    /**
     * @var EventDispatcherInterface
     */
    protected $eventDispatcher;

    /**
     * @param TmdbApiService $tmdbService
     * @param FilmographyMovieConverter $filmographyMovieConverter
     * @param ActorRepository $actorRepository
     * @param FilmographyMovieRepository $filmographyMovieRepository
     * @param EntityManagerInterface $entityManager
     * @param EventDispatcherInterface $eventDispatcher
     */
    public function __construct(
        TmdbApiService $tmdbApiService,
        FilmographyMovieConverter $filmographyMovieConverter,
        ActorRepository $actorRepository,
        FilmographyMovieRepository $filmographyMovieRepository,
        EntityManagerInterface $entityManager,
        EventDispatcherInterface $eventDispatcher
    ) {
        $this->tmdbApiService = $tmdbApiService;
        $this->filmographyMovieConverter = $filmographyMovieConverter;
        $this->actorRepository = $actorRepository;
        $this->filmographyMovieRepository = $filmographyMovieRepository;
        $this->entityManager = $entityManager;
        $this->eventDispatcher = $eventDispatcher;
    }

    public function build()
    {
        try {
            $this->clearPreviousData();

            $actors = $this->actorRepository->findAll();

            $this->eventDispatcher->dispatch(MazeEvents::BUILD_FILMOGRAPHY_START, new FilmographyStartEvent(count($actors)));

            $allMovies = $this->getAllMovies($actors);
            $filteredMovies = $this->getFilteredMovies($allMovies);
            $this->updateActorStatus($actors, $filteredMovies);

            foreach ($filteredMovies as $movie) {
                $this->entityManager->persist($movie);
            }

            $this->entityManager->flush();

            $this->eventDispatcher->dispatch(MazeEvents::BUILD_FILMOGRAPHY_END);
        } catch (\Exception $e) {
            $this->eventDispatcher->dispatch(MazeEvents::BUILD_FILMOGRAPHY_ERROR);
            throw $e;
        }
    }

    protected function clearPreviousData()
    {
        $this->actorRepository->resetActorStatus();
        $this->filmographyMovieRepository->clearFilmography();
    }

    /**
     * Returns all movies for specified actors.
     *
     * @param array $actorList
     *
     * @return FilmographyMovie[]|array Map with movie tmdbId as key and FilmographyMovie as value
     */
    protected function getAllMovies(array $actorList): array
    {
        $movieFullList = [];
        $processCount = 0;

        /** @var Actor $actor */
        foreach ($actorList as $actor) {
            $movieList = $this->tmdbApiService->getFilmography($actor->getTmdbId());

            /** @var Movie $movie */
            foreach ($movieList as $movie) {
                $filmographyMovie = $this->filmographyMovieConverter->convert($movie);

                // Add movie to full list if not yet added
                if (!isset($movieFullList[$filmographyMovie->getTmdbId()])) {
                    $movieFullList[$filmographyMovie->getTmdbId()] = $filmographyMovie;
                }

                $filmographyMovie = $movieFullList[$filmographyMovie->getTmdbId()];
                // Add current actor to movie
                // WARNING : Check if actor not already exist (a same actor can appear several times in a same movie...)
                if (0 === count($filmographyMovie->getActors()) || !$filmographyMovie->getActors()->contains($actor)) {
                    $movie->addActor($actor);
                }
            }

            // WARNING : wait between each TMDB request to not override request rate limit (4 per seconde)
            usleep(250001);

            $this->eventDispatcher->dispatch(MazeEvents::BUILD_FILMOGRAPHY_PROGRESS, new FilmographyProgressEvent(++$processCount));
        }

        return $movieFullList;
    }

    /**
     * Returns only movies with at least 2 actors.
     *
     * @param array $tmdbMovies
     *
     * @return FilmographyMovie[]|array Map with actor tmdbId as key and CastingActor as value
     */
    protected function getFilteredMovies(array $tmdbMovies): array
    {
        $filteredMovies = [];

        foreach ($tmdbMovies as $tmdbId => $movie) {
            if (count($movie->getActors()) >= 2) {
                $filteredMovies[$tmdbId] = $movie;
            }
        }

        return $filteredMovies;
    }

    /**
     * @param Actor[]|array $actors
     * @param FilmographyMovie[]|array $movies
     */
    protected function updateActorStatus(array $actors, array $movies): void
    {
        foreach ($movies as $movie) {
            /** @var Actor $actor */
            foreach ($movie->getActors() as $actor) {
                $actor->setStatus(FilmographyStatusEnum::INITIALIZED);
            }
        }

        foreach ($actors as $actor) {
            if (FilmographyStatusEnum::UNINITIALIZED === $actor->getStatus()) {
                $actor->setStatus(FilmographyStatusEnum::EMPTY);
            }
        }
    }
}
