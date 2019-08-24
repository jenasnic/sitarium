<?php

namespace App\EventListener\Maze;

use App\Entity\Maze\BuildProcess;
use App\Enum\Maze\ProcessTypeEnum;
use App\Event\MazeEvents;
use App\Event\Maze\CastingProgressEvent;
use App\Event\Maze\CastingStartEvent;
use App\Repository\Maze\BuildProcessRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

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
