<?php
namespace App\Service\Tmdb\Synchronizer;

use App\Entity\Maze\CastingActor;
use App\Model\Tmdb\Search\DisplayableInterface;
use App\Repository\Maze\CastingActorRepository;
use App\Service\Tmdb\TmdbApiService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use App\Entity\Maze\Actor;

class CastingActorSynchronizer extends AbstractSynchronizer
{
    /**
     * @var CastingActorRepository
     */
    protected $castingActorRepository;

    /**
     * @param TmdbApiService $tmdbService
     * @param EntityManagerInterface $entityManager
     * @param EventDispatcherInterface $eventDispatcher
     * @param CastingActorRepository $actorRepository
     */
    public function __construct(
        TmdbApiService $tmdbService,
        EntityManagerInterface $entityManager,
        EventDispatcherInterface $eventDispatcher,
        CastingActorRepository $castingActorRepository
    ) {
        parent::__construct($tmdbService, $entityManager, $eventDispatcher);

        $this->castingActorRepository = $castingActorRepository;
    }

    /**
     * @return string
     */
    protected function getLocalEntityClass()
    {
        return CastingActor::class;
    }

    /**
     * @return string
     */
    protected function getTmdbEntityClass()
    {
        return Actor::class;
    }

    /**
     * @return DisplayableInterface[]|array
     */
    protected function getAllData()
    {
        return $this->castingActorRepository->findAll();
    }

    /**
     * @param CastingActor $localData
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
