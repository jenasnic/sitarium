<?php

namespace App\EventListener;

use App\Enum\Maze\SessionValues;
use App\Event\Maze\CastingProgressEvent;
use App\Event\Maze\CastingStartEvent;
use App\Event\Maze\Events;
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
     * @inheritdoc
     */
    public static function getSubscribedEvents()
    {
        return [
            Events::BUILD_CASTING_START => 'onBuildCastingStart',
            Events::BUILD_CASTING_PROGRESS => 'onBuildCastingProgress',
            Events::BUILD_CASTING_END => 'onBuildCastingEnd',
        ];
    }

    /**
     * @param CastingStartEvent $event
     */
    public function onBuildCastingStart(CastingStartEvent $event)
    {
        $this->session->set(SessionValues::SESSION_BUILD_CASTING_PROGRESS, 0);
        $this->session->set(SessionValues::SESSION_BUILD_CASTING_TOTAL, $event->getTotal());
        $this->session->save();
    }

    /**
     * @param CastingProgressEvent $event
     */
    public function onBuildCastingProgress(CastingProgressEvent $event)
    {
        $this->session->set(SessionValues::SESSION_BUILD_CASTING_PROGRESS, $event->getProgress());
        $this->session->save();
    }

    /**
     * @param Event $event
     */
    public function onBuildCastingEnd(Event $event)
    {
        $this->session->remove(SessionValues::SESSION_BUILD_CASTING_PROGRESS);
        $this->session->remove(SessionValues::SESSION_BUILD_CASTING_TOTAL);
        $this->session->save();
    }
}
