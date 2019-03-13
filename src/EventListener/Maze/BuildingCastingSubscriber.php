<?php

namespace App\EventListener\Maze;

use App\Enum\Maze\SessionValueEnum;
use App\Event\Maze\CastingProgressEvent;
use App\Event\Maze\CastingStartEvent;
use App\Event\MazeEvents;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class BuildingCastingSubscriber implements EventSubscriberInterface
{
    /**
     * @var SessionInterface
     */
    protected $session;

    /**
     * @param SessionInterface $session
     */
    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
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
        $this->session->set(SessionValueEnum::SESSION_BUILD_CASTING_PROGRESS, 0);
        $this->session->set(SessionValueEnum::SESSION_BUILD_CASTING_TOTAL, $event->getTotal());
        $this->session->save();
    }

    /**
     * @param CastingProgressEvent $event
     */
    public function onBuildCastingProgress(CastingProgressEvent $event)
    {
        $this->session->set(SessionValueEnum::SESSION_BUILD_CASTING_PROGRESS, $event->getProgress());
        $this->session->save();
    }

    /**
     * @param Event $event
     */
    public function onBuildCastingEnd(Event $event)
    {
        $this->session->remove(SessionValueEnum::SESSION_BUILD_CASTING_PROGRESS);
        $this->session->remove(SessionValueEnum::SESSION_BUILD_CASTING_TOTAL);
        $this->session->save();
    }
}
