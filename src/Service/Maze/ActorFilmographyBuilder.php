<?php

namespace App\Service\Maze;

use App\Entity\Maze\FilmographyMovie;
use App\Enum\Maze\FilmographyStatus;
use App\Event\MazeEvents;
use App\Event\Maze\FilmographyProgressEvent;
use App\Event\Maze\FilmographyStartEvent;
use App\Repository\Maze\ActorRepository;
use App\Service\Tmdb\TmdbApiService;
use App\Validator\Maze\FilmographyMovieValidator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * This class allows to get all movies relative to existing actors and to build filmography keeping only movies linked together
 * (i.e. a movie with only one actor won't be kept and saved in database).
 */
class ActorFilmographyBuilder
{
    /**
     * @var TmdbApiService
     */
    protected $tmdbService;

    /**
     * @var ActorRepository
     */
    protected $actorRepository;

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
     * @param ActorRepository $actorRepository
     * @param EntityManagerInterface $entityManager
     * @param EventDispatcherInterface $eventDispatcher
     */
    public function __construct(
        TmdbApiService $tmdbService,
        ActorRepository $actorRepository,
        EntityManagerInterface $entityManager,
        EventDispatcherInterface $eventDispatcher
    ) {
        $this->tmdbService = $tmdbService;
        $this->actorRepository = $actorRepository;
        $this->entityManager = $entityManager;
        $this->eventDispatcher = $eventDispatcher;
    }

    public function build()
    {
        $actorList = $this->actorRepository->findAll();
        $movieFullList = [];
        $movieFilteredList = [];
        $processCount = 0;

        $this->eventDispatcher->dispatch(MazeEvents::BUILD_FILMOGRAPHY_START, new FilmographyStartEvent(count($actorList)));

        // First step : get all movies for all actors
        /** @var Actor $actor */
        foreach ($actorList as $actor) {
            $movieList = $this->tmdbService->getFilmographyForActorId(
                FilmographyMovie::class,
                $actor->getTmdbId(),
                'movie',
                new FilmographyMovieValidator()
            );

            /** @var FilmographyMovie $movie */
            foreach ($movieList as $movie) {
                // Add movie to full list if not yet added
                if (!isset($movieFullList[$movie->getTmdbId()])) {
                    $movieFullList[$movie->getTmdbId()] = $movie;
                }

                $movie = $movieFullList[$movie->getTmdbId()];
                // Add current actor to movie
                // WARNING : Check if actor not already exist (a same actor can appear several times in a same movie...)
                if (0 === count($movie->getActors()) || !$movie->getActors()->contains($actor)) {
                    $movie->addActor($actor);
                }
            }

            // WARNING : wait between each TMDB request to not override request rate limit (40 per seconde)
            usleep(400000);

            $this->eventDispatcher->dispatch(MazeEvents::BUILD_FILMOGRAPHY_PROGRESS, new FilmographyProgressEvent(++$processCount));
        }

        // Second step : keep only movies with at least two actors (i.e. allowing to link at least 2 actors)
        foreach ($movieFullList as $tmdbId => $movie) {
            if (count($movie->getActors()) >= 2) {
                $movieFilteredList[$tmdbId] = $movie;
                // Update status for actors
                foreach ($movie->getActors() as $actor) {
                    $actor->setStatus(FilmographyStatus::INITIALIZED);
                }
            }
        }

        // Third step : update status for actors without filmography (i.e. actor status not initialized)
        /** @var Actor $actor */
        foreach ($actorList as $actor) {
            if (FilmographyStatus::UNINITIALIZED === $actor->getStatus()) {
                $actor->setStatus(FilmographyStatus::EMPTY);
            }
        }

        // Fourth step : save movies and actors
        foreach ($movieFilteredList as $movie) {
            $this->entityManager->persist($movie);
        }

        $this->entityManager->flush();

        $this->eventDispatcher->dispatch(MazeEvents::BUILD_FILMOGRAPHY_END);
    }
}
