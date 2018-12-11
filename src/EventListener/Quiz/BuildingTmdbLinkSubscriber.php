<?php

namespace App\EventListener\Quiz;

use App\Enum\Quiz\SessionValues;
use App\Event\Quiz\TmdbLinkProgressEvent;
use App\Event\Quiz\TmdbLinkStartEvent;
use App\Event\QuizEvents;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class BuildingTmdbLinkSubscriber implements EventSubscriberInterface
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
            QuizEvents::BUILD_TMDB_LINK_START => 'onBuildTmdbLinkStart',
            QuizEvents::BUILD_TMDB_LINK_PROGRESS => 'onBuildTmdbLinkProgress',
            QuizEvents::BUILD_TMDB_LINK_END => 'onBuildTmdbLinkEnd',
        ];
    }

    /**
     * @param TmdbLinkStartEvent $event
     */
    public function onBuildTmdbLinkStart(TmdbLinkStartEvent $event)
    {
        $this->session->set(SessionValues::SESSION_BUILD_TMDB_LINK_PROGRESS, 0);
        $this->session->set(SessionValues::SESSION_BUILD_TMDB_LINK_TOTAL, $event->getTotal());
        $this->session->save();
    }

    /**
     * @param TmdbLinkProgressEvent $event
     */
    public function onBuildTmdbLinkProgress(TmdbLinkProgressEvent $event)
    {
        $this->session->set(SessionValues::SESSION_BUILD_TMDB_LINK_PROGRESS, $event->getProgress());
        $this->session->save();
    }

    /**
     * @param Event $event
     */
    public function onBuildTmdbLinkEnd(Event $event)
    {
        $this->session->remove(SessionValues::SESSION_BUILD_TMDB_LINK_PROGRESS);
        $this->session->remove(SessionValues::SESSION_BUILD_TMDB_LINK_TOTAL);
        $this->session->save();
    }
}
