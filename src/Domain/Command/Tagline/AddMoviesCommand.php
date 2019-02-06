<?php

namespace App\Domain\Command\Tagline;

class AddMoviesCommand
{
    /**
     * @var int[]|array
     */
    protected $tmdbIds;

    /**
     * @param int[]|array $tmdbId
     */
    public function __construct(array $tmdbIds)
    {
        $this->tmdbIds = $tmdbIds;
    }

    /**
     * @return int[]|array
     */
    public function getTmdbIds(): array
    {
        return $this->tmdbIds;
    }
}
