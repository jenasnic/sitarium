<?php

namespace App\EventListener\Tmdb;

use App\Entity\Tmdb\BuildProcess;
use App\Enum\Tmdb\ProcessTypeEnum;
use App\Event\Quiz\TmdbLinkEndEvent;
use App\Event\Quiz\TmdbLinkStartEvent;
use App\Event\Quiz\TmdbLinkProgressEvent;
use App\Repository\Tmdb\BuildProcessRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Contracts\EventDispatcher\Event;

class UpdateProcessForQuizLinkSubscriber implements EventSubscriberInterface
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
            TmdbLinkStartEvent::BUILD_TMDB_LINK_START => 'onBuildTmdbLinkStart',
            TmdbLinkProgressEvent::BUILD_TMDB_LINK_PROGRESS => 'onBuildTmdbLinkProgress',
            TmdbLinkEndEvent::BUILD_TMDB_LINK_END => 'onBuildTmdbLinkEnd',
        ];
    }

    /**
     * @param TmdbLinkStartEvent $event
     */
    public function onBuildTmdbLinkStart(TmdbLinkStartEvent $event)
    {
        $buildProcess = new BuildProcess(ProcessTypeEnum::QUIZ_LINK, $event->getTotal());

        $this->entityManager->persist($buildProcess);
        $this->entityManager->flush();
    }

    /**
     * @param TmdbLinkProgressEvent $event
     */
    public function onBuildTmdbLinkProgress(TmdbLinkProgressEvent $event)
    {
        $buildProcess = $this->buildProcessRepository->findPendingProcessByType(ProcessTypeEnum::QUIZ_LINK);
        $buildProcess->setCount($event->getProgress());

        $this->entityManager->flush();
    }

    /**
     * @param Event $event
     */
    public function onBuildTmdbLinkEnd(Event $event)
    {
        $buildProcess = $this->buildProcessRepository->findPendingProcessByType(ProcessTypeEnum::QUIZ_LINK);
        $buildProcess->setEndedAt(new \DateTime());

        $this->entityManager->flush();
    }
}
