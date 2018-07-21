<?php

namespace App\Service\Action;

use App\Entity\Maze\CastingActor;
use App\Enum\Maze\CastingStatus;
use App\Event\Maze\CastingProgressEvent;
use App\Event\Maze\CastingStartEvent;
use App\Event\Maze\Events;
use App\Repository\Maze\MovieRepository;
use App\Service\TmdbApiService;
use App\Validator\Maze\CastingActorValidator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * This class allows to get all actors relative to existing movies and to build casting keeping only actors linked together
 * (i.e. an actor playing in only one movie won't be kept and saved in database).
 */
class BuildCastingAction
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

    public function execute()
    {
        $movieList = $this->movieRepository->findAll();
        $actorFullList = [];
        $actorFilteredList = [];
        $processCount = 0;

        $this->eventDispatcher->dispatch(Events::BUILD_CASTING_START, new CastingStartEvent(count($movieList)));

        // First step : get all actors for all movies
        /** @var Movie $movie */
        foreach ($movieList as $movie) {
            $actorList = $this->tmdbService->getCastingForMovieId(
                CastingActor::class,
                $movie->getTmdbId(),
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

            $this->eventDispatcher->dispatch(Events::BUILD_CASTING_PROGRESS, new CastingProgressEvent(++$processCount));
        }

        // Second step : keep only actors with at least two movies (i.e. allowing to link at least 2 movies)
        foreach ($actorFullList as $tmdbId => $actor) {
            if (count($actor->getMovies()) >= 2) {
                $actorFilteredList[$tmdbId] = $actor;
                // Update status for movies
                foreach ($actor->getMovies() as $movie) {
                    $movie->setStatus(CastingStatus::INITIALIZED);
                }
            }
        }

        // Third step : update status for movies without casting (i.e. movie status not initialized)
        /** @var Movie $movie */
        foreach ($movieList as $movie) {
            if (CastingStatus::UNINITIALIZED === $movie->getStatus()) {
                $movie->setStatus(CastingStatus::EMPTY);
            }
        }

        // Fourth step : save actors and movies
        foreach ($actorFilteredList as $actor) {
            $this->entityManager->persist($actor);
        }

        $this->entityManager->flush();

        $this->eventDispatcher->dispatch(Events::BUILD_CASTING_END);
    }
}
