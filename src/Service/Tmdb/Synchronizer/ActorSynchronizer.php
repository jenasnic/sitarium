<?php

namespace App\Service\Tmdb\Synchronizer;

use App\Entity\Maze\Actor;
use App\Repository\Maze\ActorRepository;
use App\Service\Tmdb\TmdbDataProvider;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class ActorSynchronizer extends AbstractSynchronizer
{
    /**
     * @var ActorRepository
     */
    protected $actorRepository;

    /**
     * @param TmdbDataProvider $tmdbDataProvider
     * @param EntityManagerInterface $entityManager
     * @param EventDispatcherInterface $eventDispatcher
     * @param ActorRepository $actorRepository
     */
    public function __construct(
        TmdbDataProvider $tmdbDataProvider,
        EntityManagerInterface $entityManager,
        EventDispatcherInterface $eventDispatcher,
        ActorRepository $actorRepository
    ) {
        parent::__construct($tmdbDataProvider, $entityManager, $eventDispatcher);

        $this->actorRepository = $actorRepository;
    }

    /**
     * @param string $type
     *
     * @return bool
     */
    public function support($type): bool
    {
        return Actor::class === $type;
    }

    /**
     * @return Actor[]|array
     */
    protected function getAllData()
    {
        return $this->actorRepository->findAll();
    }

    /**
     * @param Actor $data
     *
     * @return bool TRUE if local data is updated, FALSE either
     */
    protected function synchronizeData($data): bool
    {
        $tmdbData = $this->tmdbDataProvider->getActor($data->getTmdbId());

        if ($data->getPictureUrl() !== $tmdbData->getProfilePath()) {
            $data->setPictureUrl($tmdbData->getProfilePath());

            return true;
        }

        return false;
    }
}
