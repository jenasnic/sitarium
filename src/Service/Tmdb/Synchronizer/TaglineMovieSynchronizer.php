<?php

namespace App\Service\Tmdb\Synchronizer;

use App\Entity\Tagline\Movie;
use App\Repository\Tagline\MovieRepository;
use App\Service\Tmdb\TmdbDataProvider;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class TaglineMovieSynchronizer extends AbstractSynchronizer
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

    public function support(string $type): bool
    {
        return Movie::class === $type;
    }

    /**
     * @return array<Movie>
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

        if ($data->getPictureUrl() !== $tmdbData->getProfilePath()) {
            $data->setPictureUrl($tmdbData->getProfilePath());

            return true;
        }

        return false;
    }
}
