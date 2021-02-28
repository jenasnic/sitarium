<?php

namespace App\Event\Maze;

use Symfony\Contracts\EventDispatcher\Event;

class CastingEndEvent extends Event
{
    public const BUILD_CASTING_END = 'build_casting_end';
}
