<?php

namespace App\EventListener\Tmdb;

use App\Entity\Tmdb\BuildProcess;
use App\Enum\Tmdb\ProcessStatusEnum;
use App\Enum\Tmdb\ProcessTypeEnum;
use App\Event\Maze\FilmographyEndEvent;
use App\Event\Maze\FilmographyErrorEvent;
use App\Event\Maze\FilmographyProgressEvent;
use App\Event\Maze\FilmographyStartEvent;
use App\Repository\Tmdb\BuildProcessRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Contracts\EventDispatcher\Event;

class UpdateProcessForFilmographySubscriber implements EventSubscriberInterface
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
            FilmographyStartEvent::BUILD_FILMOGRAPHY_START => 'onBuildFilmographyStart',
            FilmographyProgressEvent::BUILD_FILMOGRAPHY_PROGRESS => 'onBuildFilmographyProgress',
            FilmographyEndEvent::BUILD_FILMOGRAPHY_END => 'onBuildFilmographyEnd',
            FilmographyErrorEvent::BUILD_FILMOGRAPHY_ERROR => 'onBuildFilmographyError',
        ];
    }

    public function onBuildFilmographyStart(FilmographyStartEvent $event): void
    {
        $buildProcess = new BuildProcess(ProcessTypeEnum::FILMOGRAPHY, ProcessStatusEnum::PENDING, $event->getTotal());

        $this->entityManager->persist($buildProcess);
        $this->entityManager->flush();
    }

    public function onBuildFilmographyProgress(FilmographyProgressEvent $event): void
    {
        $buildProcess = $this->buildProcessRepository->findPendingProcessByType(ProcessTypeEnum::FILMOGRAPHY);
        $buildProcess->setCount($event->getProgress());

        $this->entityManager->flush();
    }

    public function onBuildFilmographyEnd(Event $event): void
    {
        $buildProcess = $this->buildProcessRepository->findPendingProcessByType(ProcessTypeEnum::FILMOGRAPHY);
        $buildProcess->setStatus(ProcessStatusEnum::SUCCESS);
        $buildProcess->setEndedAt(new DateTime());

        $this->entityManager->flush();
    }

    public function onBuildFilmographyError(Event $event): void
    {
        $buildProcess = $this->buildProcessRepository->findPendingProcessByType(ProcessTypeEnum::FILMOGRAPHY);
        $buildProcess->setStatus(ProcessStatusEnum::ERROR);
        $buildProcess->setEndedAt(new DateTime());

        $this->entityManager->flush();
    }
}
