<?php

namespace App\EventListener\Tmdb;

use App\Entity\Tmdb\BuildProcess;
use App\Enum\Tmdb\ProcessTypeEnum;
use App\Event\MazeEvents;
use App\Event\Maze\CastingProgressEvent;
use App\Event\Maze\CastingStartEvent;
use App\Repository\Tmdb\BuildProcessRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Contracts\EventDispatcher\Event;

class UpdateProcessForCastingSubscriber implements EventSubscriberInterface
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
            MazeEvents::BUILD_CASTING_START => 'onBuildCastingStart',
            MazeEvents::BUILD_CASTING_PROGRESS => 'onBuildCastingProgress',
            MazeEvents::BUILD_CASTING_END => 'onBuildCastingEnd',
            MazeEvents::BUILD_CASTING_ERROR => 'onBuildCastingEnd',
        ];
    }

    /**
     * @param CastingStartEvent $event
     */
    public function onBuildCastingStart(CastingStartEvent $event)
    {
        $buildProcess = new BuildProcess(ProcessTypeEnum::CASTING, $event->getTotal());

        $this->entityManager->persist($buildProcess);
        $this->entityManager->flush();
    }

    /**
     * @param CastingProgressEvent $event
     */
    public function onBuildCastingProgress(CastingProgressEvent $event)
    {
        $buildProcess = $this->buildProcessRepository->findPendingProcessByType(ProcessTypeEnum::CASTING);
        $buildProcess->setCount($event->getProgress());

        $this->entityManager->flush();
    }

    /**
     * @param Event $event
     */
    public function onBuildCastingEnd(Event $event)
    {
        $buildProcess = $this->buildProcessRepository->findPendingProcessByType(ProcessTypeEnum::CASTING);
        $buildProcess->setEndedAt(new \DateTime());

        $this->entityManager->flush();
    }
}
