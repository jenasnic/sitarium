<?php

namespace App\Service\Tmdb\Synchronizer;

use App\Event\SynchronizationEvents;
use App\Event\Synchronization\SynchronizationProgressEvent;
use App\Event\Synchronization\SynchronizationStartEvent;
use App\Service\Tmdb\TmdbDataProvider;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

abstract class AbstractSynchronizer implements SynchronizerInterface
{
    /**
     * @var TmdbDataProvider
     */
    protected $tmdbDataProvider;

    /**
     * @var EntityManagerInterface
     */
    protected $entityManager;

    /**
     * @var EventDispatcherInterface
     */
    protected $eventDispatcher;

    /**
     * @param TmdbDataProvider $tmdbDataProvider
     * @param EntityManagerInterface $entityManager
     * @param EventDispatcherInterface $eventDispatcher
     */
    public function __construct(
        TmdbDataProvider $tmdbDataProvider,
        EntityManagerInterface $entityManager,
        EventDispatcherInterface $eventDispatcher
    ) {
        $this->tmdbDataProvider = $tmdbDataProvider;
        $this->entityManager = $entityManager;
        $this->eventDispatcher = $eventDispatcher;
    }

    public function synchronize(): int
    {
        try {
            $datas = $this->getAllData();

            $this->eventDispatcher->dispatch(
                SynchronizationEvents::SYNCHRONIZE_DATA_START,
                new SynchronizationStartEvent(count($datas), $this->getLocalEntityClass())
            );

            $count = 0;
            $processed = 0;
            foreach ($datas as $data) {
                if ($this->synchronizeData($data)) {
                    $this->entityManager->persist($data);
                    $processed++;
                }

                // WARNING : wait between each TMDB request to not override request rate limit (4 per seconde)
                usleep(250001);

                $this->eventDispatcher->dispatch(SynchronizationEvents::SYNCHRONIZE_DATA_PROGRESS, new SynchronizationProgressEvent(++$count));
            }

            $this->entityManager->flush();
            $this->eventDispatcher->dispatch(SynchronizationEvents::SYNCHRONIZE_DATA_END);

            return $count;
        } catch (\Exception $e) {
            $this->eventDispatcher->dispatch(SynchronizationEvents::SYNCHRONIZE_DATA_ERROR);
            throw $e;
        }
    }

    /**
     * @param string $type
     *
     * @return bool
     */
    abstract public function support($type): bool;

    /**
     * @return array
     */
    abstract protected function getAllData();

    /**
     * @param mixed $data
     *
     * @return bool TRUE if data is updated, FALSE either
     */
    abstract protected function synchronizeData($data): bool;
}
