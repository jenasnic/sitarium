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
use App\Validator\Tmdb\CastingActorValidator;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * This class allows to get all actors relative to existing movies and to build casting keeping only actors linked together
 * (i.e. an actor playing in only one movie won't be kept and saved in database).
 */
class MovieCastingBuilder
{
    protected TmdbDataProvider $tmdbDataProvider;

    protected CastingActorConverter $castingActorConverter;

    protected MovieRepository $movieRepository;

    protected CastingActorRepository $castingActorRepository;

    protected EntityManagerInterface $entityManager;

    protected EventDispatcherInterface $eventDispatcher;

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

    public function build(): void
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
        } catch (Exception $e) {
            $this->eventDispatcher->dispatch(new CastingErrorEvent($e), CastingErrorEvent::BUILD_CASTING_ERROR);
        }
    }

    protected function clearPreviousData(): void
    {
        $this->movieRepository->resetMoviesStatus();
        $this->castingActorRepository->clearCasting();
    }

    /**
     * @param array<Movie> $movieList
     *
     * @return CastingActor[]|array<int, CastingActor>
     */
    protected function getAllActors(array $movieList): array
    {
        $actorFullList = [];
        $processCount = 0;

        /** @var Movie $movie */
        foreach ($movieList as $movie) {
            $actorList = $this->tmdbDataProvider->getCasting($movie->getTmdbId(), new CastingActorValidator());

            /** @var Actor $actor */
            foreach ($actorList as $actor) {
                if (!isset($actorFullList[$actor->getId()])) {
                    $actorFullList[$actor->getId()] = $this->castingActorConverter->convert($actor);
                }

                $castingActor = $actorFullList[$actor->getId()];
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
     * @param array<int, CastingActor> $tmdbActors
     *
     * @return CastingActor[]|array<int, CastingActor>
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
     * @param Movie[]|array<Movie> $movies
     * @param CastingActor[]|array<CastingActor> $actors
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
