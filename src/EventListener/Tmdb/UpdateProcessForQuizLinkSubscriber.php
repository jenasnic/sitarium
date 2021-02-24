<?php

namespace App\EventListener\Tmdb;

use App\Entity\Tmdb\BuildProcess;
use App\Enum\Tmdb\ProcessStatusEnum;
use App\Enum\Tmdb\ProcessTypeEnum;
use App\Event\Quiz\TmdbLinkEndEvent;
use App\Event\Quiz\TmdbLinkErrorEvent;
use App\Event\Quiz\TmdbLinkStartEvent;
use App\Event\Quiz\TmdbLinkProgressEvent;
use App\Repository\Tmdb\BuildProcessRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Contracts\EventDispatcher\Event;

class UpdateProcessForQuizLinkSubscriber implements EventSubscriberInterface
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
            TmdbLinkStartEvent::BUILD_TMDB_LINK_START => 'onBuildTmdbLinkStart',
            TmdbLinkProgressEvent::BUILD_TMDB_LINK_PROGRESS => 'onBuildTmdbLinkProgress',
            TmdbLinkEndEvent::BUILD_TMDB_LINK_END => 'onBuildTmdbLinkEnd',
            TmdbLinkErrorEvent::BUILD_TMDB_LINK_ERROR => 'onBuildTmdbLinkError',
        ];
    }

    public function onBuildTmdbLinkStart(TmdbLinkStartEvent $event): void
    {
        $buildProcess = new BuildProcess(ProcessTypeEnum::QUIZ_LINK, ProcessStatusEnum::PENDING, $event->getTotal());

        $this->entityManager->persist($buildProcess);
        $this->entityManager->flush();
    }

    public function onBuildTmdbLinkProgress(TmdbLinkProgressEvent $event): void
    {
        $buildProcess = $this->buildProcessRepository->findPendingProcessByType(ProcessTypeEnum::QUIZ_LINK);
        $buildProcess->setCount($event->getProgress());

        $this->entityManager->flush();
    }

    public function onBuildTmdbLinkEnd(Event $event): void
    {
        $buildProcess = $this->buildProcessRepository->findPendingProcessByType(ProcessTypeEnum::QUIZ_LINK);
        $buildProcess->setStatus(ProcessStatusEnum::SUCCESS);
        $buildProcess->setEndedAt(new DateTime());

        $this->entityManager->flush();
    }

    public function onBuildTmdbLinkError(Event $event): void
    {
        $buildProcess = $this->buildProcessRepository->findPendingProcessByType(ProcessTypeEnum::QUIZ_LINK);
        $buildProcess->setStatus(ProcessStatusEnum::ERROR);
        $buildProcess->setEndedAt(new DateTime());

        $this->entityManager->flush();
    }
}
