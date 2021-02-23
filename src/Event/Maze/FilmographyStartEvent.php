<?php

namespace App\Event\Maze;

use Symfony\Contracts\EventDispatcher\Event;

class FilmographyStartEvent extends Event
{
    public const BUILD_FILMOGRAPHY_START = 'build_filmography_start';

    protected int $total;

    public function __construct(int $total)
    {
        $this->total = $total;
    }

    public function getTotal(): int
    {
        return $this->total;
    }
}
