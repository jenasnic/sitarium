<?php

namespace App\Service\Tmdb\Synchronizer;

use App\Entity\Maze\Movie;
use App\Model\Tmdb\Search\DisplayableInterface;
use App\Repository\Maze\MovieRepository;
use App\Service\Tmdb\TmdbApiService;
use Doctrine\ORM\EntityManagerInterface;

class MovieSynchronizer extends AbstractSynchronizer
{
    /**
     * @var TmdbApiService
     */
    protected $tmdbService;
    
    /**
     * @var EntityManagerInterface
     */
    protected $entityManager;
    
    /**
     * @var MovieRepository
     */
    protected $movieRepository;
    
    /**
     * @param TmdbApiService $tmdbService
     * @param EntityManagerInterface $entityManager
     * @param MovieRepository $movieRepository
     */
    public function __construct(
        TmdbApiService $tmdbService,
        EntityManagerInterface $entityManager,
        MovieRepository $movieRepository
    ) {
        $this->tmdbService = $tmdbService;
        $this->entityManager = $entityManager;
        $this->movieRepository = $movieRepository;
    }

    /**
     * @return string
     */
    protected function getEntityClass()
    {
        return Movie::class;
    }

    /**
     * @return DisplayableInterface[]|array
     */
    protected function getAllData()
    {
        return $this->movieRepository->findAll();
    }

    /**
     * @param Movie $localData
     * @param Movie $tmdbData
     *
     * @return bool TRUE if local data is updated, FALSE either
     */
    protected function synchronizeData($localData, $tmdbData): bool
    {
        if ($localData->getPictureUrl() !== $tmdbData->getPictureUrl()) {
            $localData->setPictureUrl($tmdbData->getPictureUrl());

            return true;
        }

        return false;
    }
}
