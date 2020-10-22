<?php

namespace App\Service\Tmdb\Synchronizer;

use App\Event\SynchronizationEvents;
use App\Event\Synchronization\SynchronizationProgressEvent;
use App\Event\Synchronization\SynchronizationStartEvent;
use App\Model\Tmdb\Search\DisplayableInterface;
use App\Service\Tmdb\TmdbApiService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

abstract class AbstractSynchronizer implements SynchronizerInterface
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
     * @var EventDispatcherInterface
     */
    protected $eventDispatcher;

    /**
     * @param TmdbApiService $tmdbService
     * @param EntityManagerInterface $entityManager
     * @param EventDispatcherInterface $eventDispatcher
     */
    public function __construct(
        TmdbApiService $tmdbService,
        EntityManagerInterface $entityManager,
        EventDispatcherInterface $eventDispatcher
    ) {
        $this->tmdbService = $tmdbService;
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
                $tmdbData = $this->tmdbService->getEntity($this->getTmdbEntityClass(), $data->getTmdbId());

                if ($this->synchronizeData($data, $tmdbData)) {
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

    public function support($type): bool
    {
        return $this->getLocalEntityClass() === $type;
    }

    /**
     * @return string
     */
    abstract protected function getLocalEntityClass();

    /**
     * @return string
     */
    abstract protected function getTmdbEntityClass();

    /**
     * @return DisplayableInterface[]|array
     */
    abstract protected function getAllData();

    /**
     * @param mixed $localData
     * @param mixed $tmdbData
     * 
     * @return bool TRUE if local data is updated, FALSE either
     */
    abstract protected function synchronizeData($localData, $tmdbData): bool;
}
