<?php

namespace App\Domain\Command;

class AddActorCommand
{
    /**
     * @var int
     */
    protected $tmdbId;

    /**
     * @param int $userId
     */
    function __construct(int $tmdbId)
    {
        $this->tmdbId = $tmdbId;
    }

    /**
     * @return int
     */
    public function getTmdbId()
    {
        return $this->tmdbId;
    }
}
