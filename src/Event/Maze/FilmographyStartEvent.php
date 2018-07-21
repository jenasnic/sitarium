<?php

namespace App\Event\Maze;

use Symfony\Component\EventDispatcher\Event;

class FilmographyStartEvent extends Event
{
    /**
     * @var int
     */
    protected $total;

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

