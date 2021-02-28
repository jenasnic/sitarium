<?php

namespace App\Event\Maze;

use Symfony\Contracts\EventDispatcher\Event;

class FilmographyEndEvent extends Event
{
    public const BUILD_FILMOGRAPHY_END = 'build_filmography_end';
}
