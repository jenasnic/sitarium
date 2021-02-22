<?php

namespace App\Event\Maze;

use Symfony\Contracts\EventDispatcher\Event;

class CastingStartEvent extends Event
{
    public const BUILD_CASTING_START = 'build_casting_start';

    /**
     * @var int
     */
    protected $total;

    /**
     * @param int $total
     */
    public function __construct(int $total)
    {
        $this->total = $total;
    }

    /**
     * @return int
     */
    public function getTotal(): int
    {
        return $this->total;
    }
}
