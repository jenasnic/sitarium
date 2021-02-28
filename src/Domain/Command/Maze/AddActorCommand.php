<?php

namespace App\Domain\Command\Maze;

class AddActorCommand
{
    protected int $tmdbId;

    public function __construct(int $tmdbId)
    {
        $this->tmdbId = $tmdbId;
    }

    public function getTmdbId(): int
    {
        return $this->tmdbId;
    }
}
