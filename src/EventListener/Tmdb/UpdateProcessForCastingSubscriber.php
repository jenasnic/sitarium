<?php

namespace App\EventListener\Tmdb;

use App\Entity\Tmdb\BuildProcess;
use App\Enum\Tmdb\ProcessStatusEnum;
use App\Enum\Tmdb\ProcessTypeEnum;
use App\Event\Maze\CastingEndEvent;
use App\Event\Maze\CastingErrorEvent;
use App\Event\Maze\CastingProgressEvent;
use App\Event\Maze\CastingStartEvent;
use App\Repository\Tmdb\BuildProcessRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Contracts\EventDispatcher\Event;

class UpdateProcessForCastingSubscriber implements EventSubscriberInterface
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

    /**
     * @return array<string, string>
     */
    public static function getSubscribedEvents(): array
    {
        return [
            CastingStartEvent::BUILD_CASTING_START => 'onBuildCastingStart',
            CastingProgressEvent::BUILD_CASTING_PROGRESS => 'onBuildCastingProgress',
            CastingEndEvent::BUILD_CASTING_END => 'onBuildCastingEnd',
            CastingErrorEvent::BUILD_CASTING_ERROR => 'onBuildCastingError',
        ];
    }

    public function onBuildCastingStart(CastingStartEvent $event): void
    {
        $buildProcess = new BuildProcess(ProcessTypeEnum::CASTING, ProcessStatusEnum::PENDING, $event->getTotal());

        $this->entityManager->persist($buildProcess);
        $this->entityManager->flush();
    }

    public function onBuildCastingProgress(CastingProgressEvent $event): void
    {
        $buildProcess = $this->buildProcessRepository->findPendingProcessByType(ProcessTypeEnum::CASTING);
        $buildProcess->setCount($event->getProgress());

        $this->entityManager->flush();
    }

    public function onBuildCastingEnd(Event $event): void
    {
        $buildProcess = $this->buildProcessRepository->findPendingProcessByType(ProcessTypeEnum::CASTING);
        $buildProcess->setStatus(ProcessStatusEnum::SUCCESS);
        $buildProcess->setEndedAt(new DateTime());

        $this->entityManager->flush();
    }

    public function onBuildCastingError(Event $event): void
    {
        $buildProcess = $this->buildProcessRepository->findPendingProcessByType(ProcessTypeEnum::CASTING);
        $buildProcess->setStatus(ProcessStatusEnum::ERROR);
        $buildProcess->setEndedAt(new DateTime());

        $this->entityManager->flush();
    }
}
