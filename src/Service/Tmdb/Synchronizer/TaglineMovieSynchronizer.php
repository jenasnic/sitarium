<?php

namespace App\Service\Tmdb\Synchronizer;

use App\Entity\Tagline\Movie;
use App\Model\Tmdb\Search\DisplayableInterface;
use App\Repository\Tagline\MovieRepository;
use App\Service\Tmdb\TmdbApiService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class TaglineMovieSynchronizer extends AbstractSynchronizer
{
    /**
     * @var MovieRepository
     */
    protected $movieRepository;

    /**
     * @param TmdbApiService $tmdbService
     * @param EntityManagerInterface $entityManager
     * @param EventDispatcherInterface $eventDispatcher
     * @param MovieRepository $movieRepository
     */
    public function __construct(
        TmdbApiService $tmdbService,
        EntityManagerInterface $entityManager,
        EventDispatcherInterface $eventDispatcher,
        MovieRepository $movieRepository
    ) {
        parent::__construct($tmdbService, $entityManager, $eventDispatcher);

        $this->movieRepository = $movieRepository;
    }

    /**
     * @return string
     */
    protected function getLocalEntityClass()
    {
        return Movie::class;
    }

    /**
     * @return string
     */
    protected function getTmdbEntityClass()
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
