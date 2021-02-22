<?php

namespace App\Service\Maze;

use App\Entity\Maze\CastingActor;
use App\Entity\Maze\Movie;
use App\Enum\Maze\CastingStatusEnum;
use App\Event\Maze\CastingEndEvent;
use App\Event\Maze\CastingErrorEvent;
use App\Event\Maze\CastingProgressEvent;
use App\Event\Maze\CastingStartEvent;
use App\Model\Tmdb\Actor;
use App\Repository\Maze\CastingActorRepository;
use App\Repository\Maze\MovieRepository;
use App\Service\Converter\CastingActorConverter;
use App\Service\Tmdb\TmdbDataProvider;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * This class allows to get all actors relative to existing movies and to build casting keeping only actors linked together
 * (i.e. an actor playing in only one movie won't be kept and saved in database).
 */
class MovieCastingBuilder
{
    /**
     * @var TmdbDataProvider
     */
    protected $tmdbDataProvider;

    /**
     * @var CastingActorConverter
     */
    protected $castingActorConverter;

    /**
     * @var MovieRepository
     */
    protected $movieRepository;

    /**
     * @var CastingActorRepository
     */
    protected $castingActorRepository;

    /**
     * @var EntityManagerInterface
     */
    protected $entityManager;

    /**
     * @var EventDispatcherInterface
     */
    protected $eventDispatcher;

    /**
     * @param TmdbDataProvider $tmdbDataProvider
     * @param CastingActorConverter $castingActorConverter
     * @param MovieRepository $movieRepository
     * @param CastingActorRepository $castingActorRepository
     * @param EntityManagerInterface $entityManager
     * @param EventDispatcherInterface $eventDispatcher
     */
    public function __construct(
        TmdbDataProvider $tmdbDataProvider,
        CastingActorConverter $castingActorConverter,
        MovieRepository $movieRepository,
        CastingActorRepository $castingActorRepository,
        EntityManagerInterface $entityManager,
        EventDispatcherInterface $eventDispatcher
    ) {
        $this->tmdbDataProvider = $tmdbDataProvider;
        $this->castingActorConverter = $castingActorConverter;
        $this->movieRepository = $movieRepository;
        $this->castingActorRepository = $castingActorRepository;
        $this->entityManager = $entityManager;
        $this->eventDispatcher = $eventDispatcher;
    }

    public function build()
    {
        try {
            $this->clearPreviousData();

            $movies = $this->movieRepository->findAll();

            $this->eventDispatcher->dispatch(new CastingStartEvent(count($movies)), CastingStartEvent::BUILD_CASTING_START);

            $allActors = $this->getAllActors($movies);
            $filteredActors = $this->getFilteredActors($allActors);
            $this->updateMovieStatus($movies, $filteredActors);

            foreach ($filteredActors as $actor) {
                $this->entityManager->persist($actor);
            }

            $this->entityManager->flush();

            $this->eventDispatcher->dispatch(new CastingEndEvent(), CastingEndEvent::BUILD_CASTING_END);
        } catch (\Exception $e) {
            $this->eventDispatcher->dispatch(new CastingErrorEvent($e), CastingErrorEvent::BUILD_CASTING_ERROR);
            throw $e;
        }
    }

    protected function clearPreviousData()
    {
        $this->movieRepository->resetMoviesStatus();
        $this->castingActorRepository->clearCasting();
    }

    /**
     * Returns all actors for specified movies.
     *
     * @param array $movieList
     *
     * @return CastingActor[]|array Map with actor tmdbId as key and CastingActor as value
     */
    protected function getAllActors(array $movieList): array
    {
        $actorFullList = [];
        $processCount = 0;

        /** @var Movie $movie */
        foreach ($movieList as $movie) {
            $actorList = $this->tmdbDataProvider->getCasting($movie->getTmdbId());

            /** @var Actor $actor */
            foreach ($actorList as $actor) {
                $castingActor = $this->castingActorConverter->convert($actor);

                if (!isset($actorFullList[$castingActor->getTmdbId()])) {
                    $actorFullList[$castingActor->getTmdbId()] = $actor;
                }

                $castingActor = $actorFullList[$castingActor->getTmdbId()];
                $castingActor->addMovie($movie);
            }

            // WARNING : wait between each TMDB request to not override request rate limit (4 per seconde)
            usleep(250001);

            $this->eventDispatcher->dispatch(new CastingProgressEvent(++$processCount), CastingProgressEvent::BUILD_CASTING_PROGRESS);
        }

        return $actorFullList;
    }

    /**
     * Returns only actors with at least 2 movies.
     *
     * @param array $tmdbActors
     *
     * @return CastingActor[]|array Map with actor tmdbId as key and CastingActor as value
     */
    protected function getFilteredActors(array $tmdbActors): array
    {
        $filteredActors = [];

        foreach ($tmdbActors as $tmdbId => $actor) {
            if (count($actor->getMovies()) >= 2) {
                $filteredActors[$tmdbId] = $actor;
            }
        }

        return $filteredActors;
    }

    /**
     * @param Movie[]|array $movies
     * @param CastingActor[]|array $actors
     */
    protected function updateMovieStatus(array $movies, array $actors): void
    {
        foreach ($actors as $actor) {
            /** @var Movie $movie */
            foreach ($actor->getMovies() as $movie) {
                $movie->setStatus(CastingStatusEnum::INITIALIZED);
            }
        }

        foreach ($movies as $movie) {
            if (CastingStatusEnum::UNINITIALIZED === $movie->getStatus()) {
                $movie->setStatus(CastingStatusEnum::EMPTY);
            }
        }
    }
}
