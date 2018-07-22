<?php

namespace App\EventListener;

use App\Enum\Maze\SessionValues;
use App\Event\MazeEvents;
use App\Event\Maze\FilmographyProgressEvent;
use App\Event\Maze\FilmographyStartEvent;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class BuildingFilmographySubscriber implements EventSubscriberInterface
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
            MazeEvents::BUILD_FILMOGRAPHY_START => 'onBuildFilmographyStart',
            MazeEvents::BUILD_FILMOGRAPHY_PROGRESS => 'onBuildFilmographyProgress',
            MazeEvents::BUILD_FILMOGRAPHY_END => 'onBuildFilmographyEnd',
        ];
    }

    /**
     * @param FilmographyStartEvent $event
     */
    public function onBuildFilmographyStart(FilmographyStartEvent $event)
    {
        $this->session->set(SessionValues::SESSION_BUILD_FILMOGRAPHY_PROGRESS, 0);
        $this->session->set(SessionValues::SESSION_BUILD_FILMOGRAPHY_TOTAL, $event->getTotal());
        $this->session->save();
    }

    /**
     * @param FilmographyProgressEvent $event
     */
    public function onBuildFilmographyProgress(FilmographyProgressEvent $event)
    {
        $this->session->set(SessionValues::SESSION_BUILD_FILMOGRAPHY_PROGRESS, $event->getProgress());
        $this->session->save();
    }

    /**
     * @param Event $event
     */
    public function onBuildFilmographyEnd(Event $event)
    {
        $this->session->remove(SessionValues::SESSION_BUILD_FILMOGRAPHY_PROGRESS);
        $this->session->remove(SessionValues::SESSION_BUILD_FILMOGRAPHY_TOTAL);
        $this->session->save();
    }
}
