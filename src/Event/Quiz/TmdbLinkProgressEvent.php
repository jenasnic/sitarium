<?php

namespace App\Event\Quiz;

use Symfony\Contracts\EventDispatcher\Event;

class TmdbLinkProgressEvent extends Event
{
    public const BUILD_TMDB_LINK_PROGRESS = 'build_tmdb_link_progress';

    protected int $progress;

    public function __construct(int $progress)
    {
        $this->progress = $progress;
    }

    public function getProgress(): int
    {
        return $this->progress;
    }
}
