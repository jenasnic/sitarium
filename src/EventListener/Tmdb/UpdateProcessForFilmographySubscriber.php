<?php

namespace App\EventListener\Tmdb;

use App\Entity\Tmdb\BuildProcess;
use App\Enum\Tmdb\ProcessTypeEnum;
use App\Event\Maze\FilmographyEndEvent;
use App\Event\Maze\FilmographyErrorEvent;
use App\Event\Maze\FilmographyProgressEvent;
use App\Event\Maze\FilmographyStartEvent;
use App\Repository\Tmdb\BuildProcessRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Contracts\EventDispatcher\Event;

class UpdateProcessForFilmographySubscriber implements EventSubscriberInterface
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
            FilmographyStartEvent::BUILD_FILMOGRAPHY_START => 'onBuildFilmographyStart',
            FilmographyProgressEvent::BUILD_FILMOGRAPHY_PROGRESS => 'onBuildFilmographyProgress',
            FilmographyEndEvent::BUILD_FILMOGRAPHY_END => 'onBuildFilmographyEnd',
            FilmographyErrorEvent::BUILD_FILMOGRAPHY_ERROR => 'onBuildFilmographyEnd',
        ];
    }

    /**
     * @param FilmographyStartEvent $event
     */
    public function onBuildFilmographyStart(FilmographyStartEvent $event)
    {
        $buildProcess = new BuildProcess(ProcessTypeEnum::FILMOGRAPHY, $event->getTotal());

        $this->entityManager->persist($buildProcess);
        $this->entityManager->flush();
    }

    /**
     * @param FilmographyProgressEvent $event
     */
    public function onBuildFilmographyProgress(FilmographyProgressEvent $event)
    {
        $buildProcess = $this->buildProcessRepository->findPendingProcessByType(ProcessTypeEnum::FILMOGRAPHY);
        $buildProcess->setCount($event->getProgress());

        $this->entityManager->flush();
    }

    /**
     * @param Event $event
     */
    public function onBuildFilmographyEnd(Event $event)
    {
        $buildProcess = $this->buildProcessRepository->findPendingProcessByType(ProcessTypeEnum::FILMOGRAPHY);
        $buildProcess->setEndedAt(new \DateTime());

        $this->entityManager->flush();
    }
}
