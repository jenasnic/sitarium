<?php

namespace App\Service\Tmdb\Synchronizer;

use App\Entity\Maze\Actor;
use App\Model\Tmdb\Search\DisplayableInterface;
use App\Repository\Maze\ActorRepository;
use App\Service\Tmdb\TmdbApiService;
use Doctrine\ORM\EntityManagerInterface;

class ActorSynchronizer extends AbstractSynchronizer
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
     * @var ActorRepository
     */
    protected $actorRepository;

    /**
     * @param TmdbApiService $tmdbService
     * @param EntityManagerInterface $entityManager
     * @param ActorRepository $actorRepository
     */
    public function __construct(
        TmdbApiService $tmdbService,
        EntityManagerInterface $entityManager,
        ActorRepository $actorRepository
    ) {
        $this->tmdbService = $tmdbService;
        $this->entityManager = $entityManager;
        $this->actorRepository = $actorRepository;
    }

    /**
     * @return string
     */
    protected function getEntityClass()
    {
        return Actor::class;
    }

    /**
     * @return DisplayableInterface[]|array
     */
    protected function getAllData()
    {
        return $this->actorRepository->findAll();
    }

    /**
     * @param Actor $localData
     * @param Actor $tmdbData
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
