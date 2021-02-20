<?php

namespace App\Service\Tmdb\Synchronizer;

use App\Entity\Maze\Movie;
use App\Repository\Maze\MovieRepository;
use App\Service\Tmdb\TmdbDataProvider;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class MovieSynchronizer extends AbstractSynchronizer
{
    /**
     * @var MovieRepository
     */
    protected $movieRepository;
    
    /**
     * @param TmdbDataProvider $tmdbDataProvider
     * @param EntityManagerInterface $entityManager
     * @param EventDispatcherInterface $eventDispatcher
     * @param MovieRepository $movieRepository
     */
    public function __construct(
        TmdbDataProvider $tmdbDataProvider,
        EntityManagerInterface $entityManager,
        EventDispatcherInterface $eventDispatcher,
        MovieRepository $movieRepository
    ) {
        parent::__construct($tmdbDataProvider, $entityManager, $eventDispatcher);

        $this->movieRepository = $movieRepository;
    }

    /**
     * @param string $type
     *
     * @return bool
     */
    public function support($type): bool
    {
        return Movie::class === $type;
    }

    /**
     * @return array
     */
    protected function getAllData()
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
