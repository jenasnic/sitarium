<?php

namespace App\Domain\Command\Maze;

class AddActorCommand
{
    /**
     * @var int
     */
    protected $tmdbId;

    /**
     * @param int $tmdbId
     */
    public function __construct(int $tmdbId)
    {
        $this->tmdbId = $tmdbId;
    }

    /**
     * @return int
     */
    public function getTmdbId(): int
    {
        return $this->tmdbId;
    }
}
