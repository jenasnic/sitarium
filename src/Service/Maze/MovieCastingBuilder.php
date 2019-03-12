<?php

namespace App\Service\Maze;

use App\Entity\Maze\CastingActor;
use App\Enum\Maze\CastingStatusEnum;
use App\Event\Maze\CastingProgressEvent;
use App\Event\Maze\CastingStartEvent;
use App\Event\MazeEvents;
use App\Repository\Maze\MovieRepository;
use App\Service\Tmdb\TmdbApiService;
use App\Validator\Maze\CastingActorValidator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * This class allows to get all actors relative to existing movies and to build casting keeping only actors linked together
 * (i.e. an actor playing in only one movie won't be kept and saved in database).
 */
class MovieCastingBuilder
{
    /**
     * @var TmdbApiService
     */
    protected $tmdbService;

    /**
     * @var MovieRepository
     */
    protected $movieRepository;

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
     * @param MovieRepository $movieRepository
     * @param EntityManagerInterface $entityManager
     * @param EventDispatcherInterface $eventDispatcher
     */
    public function __construct(
        TmdbApiService $tmdbService,
        MovieRepository $movieRepository,
        EntityManagerInterface $entityManager,
        EventDispatcherInterface $eventDispatcher
    ) {
        $this->tmdbService = $tmdbService;
        $this->movieRepository = $movieRepository;
        $this->entityManager = $entityManager;
        $this->eventDispatcher = $eventDispatcher;
    }

    public function build()
    {
        $movieList = $this->movieRepository->findAll();
        $actorFullList = [];
        $actorFilteredList = [];
        $processCount = 0;

        $this->eventDispatcher->dispatch(MazeEvents::BUILD_CASTING_START, new CastingStartEvent(count($movieList)));

        // First step : get all actors for all movies
        /** @var Movie $movie */
        foreach ($movieList as $movie) {
            $actorList = $this->tmdbService->getCastingForMovieId(
                $movie->getTmdbId(),
                CastingActor::class,
                new CastingActorValidator()
            );

            /** @var CastingActor $actor */
            foreach ($actorList as $actor) {
                if (!isset($actorFullList[$actor->getTmdbId()])) {
                    $actorFullList[$actor->getTmdbId()] = $actor;
                }

                $actor = $actorFullList[$actor->getTmdbId()];
                $actor->addMovie($movie);
            }

            // WARNING : wait between each TMDB request to not override request rate limit (40 per seconde)
            usleep(400000);

            $this->eventDispatcher->dispatch(MazeEvents::BUILD_CASTING_PROGRESS, new CastingProgressEvent(++$processCount));
        }

        // Second step : keep only actors with at least two movies (i.e. allowing to link at least 2 movies)
        foreach ($actorFullList as $tmdbId => $actor) {
            if (count($actor->getMovies()) >= 2) {
                $actorFilteredList[$tmdbId] = $actor;
                // Update status for movies
                foreach ($actor->getMovies() as $movie) {
                    $movie->setStatus(CastingStatusEnum::INITIALIZED);
                }
            }
        }

        // Third step : update status for movies without casting (i.e. movie status not initialized)
        /** @var Movie $movie */
        foreach ($movieList as $movie) {
            if (CastingStatusEnum::UNINITIALIZED === $movie->getStatus()) {
                $movie->setStatus(CastingStatusEnum::EMPTY);
            }
        }

        // Fourth step : save actors and movies
        foreach ($actorFilteredList as $actor) {
            $this->entityManager->persist($actor);
        }

        $this->entityManager->flush();

        $this->eventDispatcher->dispatch(MazeEvents::BUILD_CASTING_END);
    }
}
