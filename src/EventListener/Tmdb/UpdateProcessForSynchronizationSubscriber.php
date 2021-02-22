<?php

namespace App\EventListener\Tmdb;

use App\Entity\Tmdb\BuildProcess;
use App\Enum\Tmdb\ProcessTypeEnum;
use App\Event\Synchronization\SynchronizationEndEvent;
use App\Event\Synchronization\SynchronizationErrorEvent;
use App\Event\Synchronization\SynchronizationProgressEvent;
use App\Event\Synchronization\SynchronizationStartEvent;
use App\Repository\Tmdb\BuildProcessRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Contracts\EventDispatcher\Event;

class UpdateProcessForSynchronizationSubscriber implements EventSubscriberInterface
{
    /**
     * @var BuildProcessRepository
     */
    protected $buildProcessRepository;

    /**
     * @var EntityManagerInterface
     */
    protected $entityManager;

    /**
     * @param BuildProcessRepository $buildProcessRepository
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(
        BuildProcessRepository $buildProcessRepository,
        EntityManagerInterface $entityManager
    ) {
        $this->buildProcessRepository = $buildProcessRepository;
        $this->entityManager = $entityManager;
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            SynchronizationStartEvent::SYNCHRONIZE_DATA_START => 'onSynchronizationStart',
            SynchronizationProgressEvent::SYNCHRONIZE_DATA_PROGRESS => 'onSynchronizationProgress',
            SynchronizationEndEvent::SYNCHRONIZE_DATA_END => 'onSynchronizationEnd',
            SynchronizationErrorEvent::SYNCHRONIZE_DATA_ERROR => 'onSynchronizationEnd',
        ];
    }

    /**
     * @param SynchronizationStartEvent $event
     */
    public function onSynchronizationStart(SynchronizationStartEvent $event)
    {
        $buildProcess = new BuildProcess(
            ProcessTypeEnum::SYNCHRONIZATION,
            $event->getTotal(),
            $event->getEntityClass()
        );

        $this->entityManager->persist($buildProcess);
        $this->entityManager->flush();
    }

    /**
     * @param SynchronizationProgressEvent $event
     */
    public function onSynchronizationProgress(SynchronizationProgressEvent $event)
    {
        $buildProcess = $this->buildProcessRepository->findPendingProcessByType(ProcessTypeEnum::SYNCHRONIZATION);
        $buildProcess->setCount($event->getProgress());

        $this->entityManager->flush();
    }

    /**
     * @param Event $event
     */
    public function onSynchronizationEnd(Event $event)
    {
        $buildProcess = $this->buildProcessRepository->findPendingProcessByType(ProcessTypeEnum::SYNCHRONIZATION);
        $buildProcess->setEndedAt(new \DateTime());

        $this->entityManager->flush();
    }
}
