<?php

namespace App\Event\Maze;

use Symfony\Contracts\EventDispatcher\Event;

class CastingProgressEvent extends Event
{
    public const BUILD_CASTING_PROGRESS = 'build_casting_progress';

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
