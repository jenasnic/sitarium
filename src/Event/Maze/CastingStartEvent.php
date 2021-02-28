<?php

namespace App\Event\Maze;

use Symfony\Contracts\EventDispatcher\Event;

class CastingStartEvent extends Event
{
    public const BUILD_CASTING_START = 'build_casting_start';

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
