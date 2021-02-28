<?php

namespace App\Service\Tmdb\Synchronizer;

use App\Entity\Maze\Movie;
use App\Repository\Maze\MovieRepository;
use App\Service\Tmdb\TmdbDataProvider;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * @extends AbstractSynchronizer<Movie>
 */
class MovieSynchronizer extends AbstractSynchronizer
{
    protected MovieRepository $movieRepository;

    public function __construct(
        TmdbDataProvider $tmdbDataProvider,
        EntityManagerInterface $entityManager,
        EventDispatcherInterface $eventDispatcher,
        MovieRepository $movieRepository
    ) {
        parent::__construct($tmdbDataProvider, $entityManager, $eventDispatcher);

        $this->movieRepository = $movieRepository;
    }

    protected function getType(): string
    {
        return Movie::class;
    }

    /**
     * @return Movie[]|array<Movie>
     */
    protected function getAllData(): array
    {
        return $this->movieRepository->findAll();
    }

    /**
     * @param Movie $data
     *
     * @return bool TRUE if local data is updated, FALSE either
     */
    protected function synchronizeData($data): bool
    {
        $tmdbData = $this->tmdbDataProvider->getMovie($data->getTmdbId());

        $isUpdated = false;

        if ($data->getPictureUrl() !== $tmdbData->getPosterPath()) {
            $data->setPictureUrl($tmdbData->getPosterPath());
            $isUpdated = true;
        }

        if ($data->getTitle() !== $tmdbData->getTitle()) {
            $data->setTitle($tmdbData->getTitle());
            $isUpdated = true;
        }

        return $isUpdated;
    }
}
