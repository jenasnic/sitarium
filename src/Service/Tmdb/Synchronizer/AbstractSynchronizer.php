<?php

namespace App\Service\Tmdb\Synchronizer;

use App\Event\Synchronization\SynchronizationEndEvent;
use App\Event\Synchronization\SynchronizationErrorEvent;
use App\Event\Synchronization\SynchronizationProgressEvent;
use App\Event\Synchronization\SynchronizationStartEvent;
use App\Service\Tmdb\TmdbDataProvider;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * @psalm-template T
 */
abstract class AbstractSynchronizer implements SynchronizerInterface
{
    protected TmdbDataProvider $tmdbDataProvider;

    protected EntityManagerInterface $entityManager;

    protected EventDispatcherInterface $eventDispatcher;

    public function __construct(
        TmdbDataProvider $tmdbDataProvider,
        EntityManagerInterface $entityManager,
        EventDispatcherInterface $eventDispatcher
    ) {
        $this->tmdbDataProvider = $tmdbDataProvider;
        $this->entityManager = $entityManager;
        $this->eventDispatcher = $eventDispatcher;
    }

    public function synchronize(): void
    {
        $count = 0;
        $processed = 0;

        try {
            $datas = $this->getAllData();

            $this->eventDispatcher->dispatch(
                new SynchronizationStartEvent(count($datas), $this->getType()),
                SynchronizationStartEvent::SYNCHRONIZE_DATA_START
            );

            foreach ($datas as $data) {
                if ($this->synchronizeData($data)) {
                    $this->entityManager->persist($data);
                    ++$processed;
                }

                // WARNING : wait between each TMDB request to not override request rate limit (4 per seconde)
                usleep(250001);

                $this->eventDispatcher->dispatch(new SynchronizationProgressEvent(++$count), SynchronizationProgressEvent::SYNCHRONIZE_DATA_PROGRESS);
            }

            $this->entityManager->flush();
            $this->eventDispatcher->dispatch(new SynchronizationEndEvent(), SynchronizationEndEvent::SYNCHRONIZE_DATA_END);
        } catch (Exception $e) {
            $this->eventDispatcher->dispatch(new SynchronizationErrorEvent($e), SynchronizationErrorEvent::SYNCHRONIZE_DATA_ERROR);
        }
    }

    public function support(string $type): bool
    {
        return $type === $this->getType();
    }

    abstract protected function getType(): string;

    /**
     * @psalm-return array<T>
     */
    abstract protected function getAllData(): array;

    /**
     * @psalm-var T $data
     *
     * @param mixed $data
     *
     * @return bool TRUE if data is updated, FALSE either
     */
    abstract protected function synchronizeData($data): bool;
}
