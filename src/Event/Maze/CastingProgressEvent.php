<?php

namespace App\Event\Maze;

use Symfony\Component\EventDispatcher\Event;

class CastingProgressEvent extends Event
{
    /**
     * @var int
     */
    protected $progress;

    public function __construct(int $progress)
    {
        $this->progress = $progress;
    }

    /**
     * @return int
     */
    public function getProgress(): int
    {
        return $this->progress;
    }
}

