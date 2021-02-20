<?php
namespace App\Service\Tmdb\Synchronizer;

use App\Entity\Maze\CastingActor;
use App\Repository\Maze\CastingActorRepository;
use App\Service\Tmdb\TmdbDataProvider;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class CastingActorSynchronizer extends AbstractSynchronizer
{
    /**
     * @var CastingActorRepository
     */
    protected $castingActorRepository;

    /**
     * @param TmdbDataProvider $tmdbDataProvider
     * @param EntityManagerInterface $entityManager
     * @param EventDispatcherInterface $eventDispatcher
     * @param CastingActorRepository $actorRepository
     */
    public function __construct(
        TmdbDataProvider $tmdbDataProvider,
        EntityManagerInterface $entityManager,
        EventDispatcherInterface $eventDispatcher,
        CastingActorRepository $castingActorRepository
    ) {
        parent::__construct($tmdbDataProvider, $entityManager, $eventDispatcher);

        $this->castingActorRepository = $castingActorRepository;
    }

    /**
     * @param string $type
     *
     * @return bool
     */
    public function support($type): bool
    {
        return CastingActor::class === $type;
    }

    /**
     * @return array
     */
    protected function getAllData()
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

        if ($data->getPictureUrl() !== $tmdbData->getProfilePath()) {
            $data->setPictureUrl($tmdbData->getProfilePath());

            return true;
        }

        return false;
    }
}
