<?php

namespace App\Service\Tmdb\Synchronizer;

use App\Entity\Maze\Actor;
use App\Repository\Maze\ActorRepository;
use App\Service\Tmdb\TmdbDataProvider;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * @extends AbstractSynchronizer<Actor>
 */
class ActorSynchronizer extends AbstractSynchronizer
{
    protected ActorRepository $actorRepository;

    public function __construct(
        TmdbDataProvider $tmdbDataProvider,
        EntityManagerInterface $entityManager,
        EventDispatcherInterface $eventDispatcher,
        ActorRepository $actorRepository
    ) {
        parent::__construct($tmdbDataProvider, $entityManager, $eventDispatcher);

        $this->actorRepository = $actorRepository;
    }

    protected function getType(): string
    {
        return Actor::class;
    }

    /**
     * @return Actor[]|array<Actor>
     */
    protected function getAllData(): array
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

        $isUpdated = false;

        if ($data->getPictureUrl() !== $tmdbData->getProfilePath()) {
            $data->setPictureUrl($tmdbData->getProfilePath());
            $isUpdated = true;
        }

        if ($data->getFullname() !== $tmdbData->getName()) {
            $data->setFullname($tmdbData->getName());
            $isUpdated = true;
        }

        return $isUpdated;
    }
}
