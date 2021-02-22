<?php

namespace App\Event\Quiz;

use Symfony\Contracts\EventDispatcher\Event;

class TmdbLinkStartEvent extends Event
{
    public const BUILD_TMDB_LINK_START = 'build_tmdb_link_start';

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
