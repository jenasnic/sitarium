<?php

namespace App\Event\Maze;

use Symfony\Contracts\EventDispatcher\Event;

class FilmographyProgressEvent extends Event
{
    public const BUILD_FILMOGRAPHY_PROGRESS = 'build_filmography_progress';

    /**
     * @var int
     */
    protected $progress;

    /**
     * @param int $progress
     */
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
