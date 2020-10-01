<?php
namespace App\Service\Tmdb\Synchronizer;

use App\Entity\Maze\CastingActor;
use App\Model\Tmdb\Search\DisplayableInterface;
use App\Repository\Maze\CastingActorRepository;
use App\Service\Tmdb\TmdbApiService;
use Doctrine\ORM\EntityManagerInterface;

class CastingActorSynchronizer extends AbstractSynchronizer
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
     * @var CastingActorRepository
     */
    protected $castingActorRepository;

    /**
     * @param TmdbApiService $tmdbService
     * @param EntityManagerInterface $entityManager
     * @param CastingActorRepository $actorRepository
     */
    public function __construct(
        TmdbApiService $tmdbService,
        EntityManagerInterface $entityManager,
        CastingActorRepository $castingActorRepository
    ) {
        $this->tmdbService = $tmdbService;
        $this->entityManager = $entityManager;
        $this->castingActorRepository = $castingActorRepository;
    }

    /**
     * @return string
     */
    protected function getEntityClass()
    {
        return CastingActor::class;
    }

    /**
     * @return DisplayableInterface[]|array
     */
    protected function getAllData()
    {
        return $this->actorRepository->findAll();
    }

    /**
     * @param CastingActor $localData
     * @param CastingActor $tmdbData
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
