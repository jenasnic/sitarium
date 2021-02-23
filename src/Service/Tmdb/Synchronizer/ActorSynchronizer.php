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

    /**
     * @param string $type
     *
     * @return bool
     */
    public function support(string $type): bool
    {
        return Actor::class === $type;
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

        if ($data->getPictureUrl() !== $tmdbData->getProfilePath()) {
            $data->setPictureUrl($tmdbData->getProfilePath());

            return true;
        }

        return false;
    }
}
