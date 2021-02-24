<?php
namespace App\Service\Tmdb\Synchronizer;

use App\Entity\Maze\CastingActor;
use App\Repository\Maze\CastingActorRepository;
use App\Service\Tmdb\TmdbDataProvider;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * @extends AbstractSynchronizer<CastingActor>
 */
class CastingActorSynchronizer extends AbstractSynchronizer
{
    protected CastingActorRepository $castingActorRepository;

    public function __construct(
        TmdbDataProvider $tmdbDataProvider,
        EntityManagerInterface $entityManager,
        EventDispatcherInterface $eventDispatcher,
        CastingActorRepository $castingActorRepository
    ) {
        parent::__construct($tmdbDataProvider, $entityManager, $eventDispatcher);

        $this->castingActorRepository = $castingActorRepository;
    }

    protected function getType(): string
    {
        return CastingActor::class;
    }

    /**
     * @return CastingActor[]|array<CastingActor>
     */
    protected function getAllData(): array
    {
        return $this->castingActorRepository->findAll();
    }

    /**
     * @param CastingActor $data
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
