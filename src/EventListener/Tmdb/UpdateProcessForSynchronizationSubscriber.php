<?php

namespace App\EventListener\Tmdb;

use App\Entity\Tmdb\BuildProcess;
use App\Enum\Tmdb\ProcessStatusEnum;
use App\Enum\Tmdb\ProcessTypeEnum;
use App\Event\Synchronization\SynchronizationEndEvent;
use App\Event\Synchronization\SynchronizationErrorEvent;
use App\Event\Synchronization\SynchronizationProgressEvent;
use App\Event\Synchronization\SynchronizationStartEvent;
use App\Repository\Tmdb\BuildProcessRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Contracts\EventDispatcher\Event;

class UpdateProcessForSynchronizationSubscriber implements EventSubscriberInterface
{
    protected BuildProcessRepository $buildProcessRepository;

    protected EntityManagerInterface $entityManager;

    public function __construct(
        BuildProcessRepository $buildProcessRepository,
        EntityManagerInterface $entityManager
    ) {
        $this->buildProcessRepository = $buildProcessRepository;
        $this->entityManager = $entityManager;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            SynchronizationStartEvent::SYNCHRONIZE_DATA_START => 'onSynchronizationStart',
            SynchronizationProgressEvent::SYNCHRONIZE_DATA_PROGRESS => 'onSynchronizationProgress',
            SynchronizationEndEvent::SYNCHRONIZE_DATA_END => 'onSynchronizationEnd',
            SynchronizationErrorEvent::SYNCHRONIZE_DATA_ERROR => 'onSynchronizationError',
        ];
    }

    public function onSynchronizationStart(SynchronizationStartEvent $event): void
    {
        $buildProcess = new BuildProcess(
            ProcessTypeEnum::SYNCHRONIZATION,
            ProcessStatusEnum::PENDING,
            $event->getTotal(),
            $event->getEntityClass()
        );

        $this->entityManager->persist($buildProcess);
        $this->entityManager->flush();
    }

    public function onSynchronizationProgress(SynchronizationProgressEvent $event): void
    {
        $buildProcess = $this->buildProcessRepository->findPendingProcessByType(ProcessTypeEnum::SYNCHRONIZATION);
        $buildProcess->setCount($event->getProgress());

        $this->entityManager->flush();
    }

    public function onSynchronizationEnd(Event $event): void
    {
        $buildProcess = $this->buildProcessRepository->findPendingProcessByType(ProcessTypeEnum::SYNCHRONIZATION);
        $buildProcess->setStatus(ProcessStatusEnum::SUCCESS);
        $buildProcess->setEndedAt(new DateTime());

        $this->entityManager->flush();
    }

    public function onSynchronizationError(Event $event): void
    {
        $buildProcess = $this->buildProcessRepository->findPendingProcessByType(ProcessTypeEnum::SYNCHRONIZATION);
        $buildProcess->setStatus(ProcessStatusEnum::ERROR);
        $buildProcess->setEndedAt(new DateTime());

        $this->entityManager->flush();
    }
}
