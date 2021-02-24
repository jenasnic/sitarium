<?php

namespace App\Service\Maze;

use App\Entity\Maze\Actor;
use App\Entity\Maze\FilmographyMovie;
use App\Enum\Maze\FilmographyStatusEnum;
use App\Event\Maze\FilmographyEndEvent;
use App\Event\Maze\FilmographyErrorEvent;
use App\Event\Maze\FilmographyProgressEvent;
use App\Event\Maze\FilmographyStartEvent;
use App\Model\Tmdb\Movie;
use App\Repository\Maze\ActorRepository;
use App\Repository\Maze\FilmographyMovieRepository;
use App\Service\Converter\FilmographyMovieConverter;
use App\Service\Tmdb\TmdbDataProvider;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use App\Validator\Tmdb\FilmographyMovieValidator;

/**
 * This class allows to get all movies relative to existing actors and to build filmography keeping only movies linked together
 * (i.e. a movie with only one actor won't be kept and saved in database).
 */
class ActorFilmographyBuilder
{
    protected TmdbDataProvider $tmdbDataProvider;

    protected FilmographyMovieConverter $filmographyMovieConverter;

    protected ActorRepository $actorRepository;

    protected FilmographyMovieRepository $filmographyMovieRepository;

    protected EntityManagerInterface $entityManager;

    protected EventDispatcherInterface $eventDispatcher;

    public function __construct(
        TmdbDataProvider $tmdbDataProvider,
        FilmographyMovieConverter $filmographyMovieConverter,
        ActorRepository $actorRepository,
        FilmographyMovieRepository $filmographyMovieRepository,
        EntityManagerInterface $entityManager,
        EventDispatcherInterface $eventDispatcher
    ) {
        $this->tmdbDataProvider = $tmdbDataProvider;
        $this->filmographyMovieConverter = $filmographyMovieConverter;
        $this->actorRepository = $actorRepository;
        $this->filmographyMovieRepository = $filmographyMovieRepository;
        $this->entityManager = $entityManager;
        $this->eventDispatcher = $eventDispatcher;
    }

    public function build(): void
    {
        try {
            $this->clearPreviousData();

            $actors = $this->actorRepository->findAll();

            $this->eventDispatcher->dispatch(new FilmographyStartEvent(count($actors)), FilmographyStartEvent::BUILD_FILMOGRAPHY_START);

            $allMovies = $this->getAllMovies($actors);
            $filteredMovies = $this->getFilteredMovies($allMovies);
            $this->updateActorStatus($actors, $filteredMovies);

            foreach ($filteredMovies as $movie) {
                $this->entityManager->persist($movie);
            }

            $this->entityManager->flush();

            $this->eventDispatcher->dispatch(new FilmographyEndEvent(), FilmographyEndEvent::BUILD_FILMOGRAPHY_END);
        } catch (Exception $e) {
            $this->eventDispatcher->dispatch(new FilmographyErrorEvent($e), FilmographyErrorEvent::BUILD_FILMOGRAPHY_ERROR);
        }
    }

    protected function clearPreviousData(): void
    {
        $this->actorRepository->resetActorStatus();
        $this->filmographyMovieRepository->clearFilmography();
    }

    /**
     * @param array<Actor> $actorList
     *
     * @return FilmographyMovie[]|array<int, FilmographyMovie>
     */
    protected function getAllMovies(array $actorList): array
    {
        $movieFullList = [];
        $processCount = 0;

        /** @var Actor $actor */
        foreach ($actorList as $actor) {
            $movieList = $this->tmdbDataProvider->getFilmography($actor->getTmdbId(), new FilmographyMovieValidator());

            /** @var Movie $movie */
            foreach ($movieList as $movie) {
                if (!isset($movieFullList[$movie->getId()])) {
                    $movieFullList[$movie->getId()] = $this->filmographyMovieConverter->convert($movie);
                }

                $filmographyMovie = $movieFullList[$movie->getId()];
                // Add current actor to movie
                // WARNING : Check if actor not already exist (a same actor can appear several times in a same movie...)
                if (0 === count($filmographyMovie->getActors()) || !$filmographyMovie->getActors()->contains($actor)) {
                    $filmographyMovie->addActor($actor);
                }
            }

            // WARNING : wait between each TMDB request to not override request rate limit (4 per seconde)
            usleep(250001);

            $this->eventDispatcher->dispatch(new FilmographyProgressEvent(++$processCount), FilmographyProgressEvent::BUILD_FILMOGRAPHY_PROGRESS);
        }

        return $movieFullList;
    }

    /**
     * Returns only movies with at least 2 actors.
     *
     * @param array<int, FilmographyMovie> $tmdbMovies
     *
     * @return FilmographyMovie[]|array<int, FilmographyMovie>
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
     * @param Actor[]|array<Actor> $actors
     * @param FilmographyMovie[]|array<FilmographyMovie> $movies
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
