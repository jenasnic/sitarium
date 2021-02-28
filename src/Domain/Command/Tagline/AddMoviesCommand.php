<?php

namespace App\Domain\Command\Tagline;

class AddMoviesCommand
{
    /**
     * @var array<int>
     */
    protected array $tmdbIds;

    /**
     * @param array<int> $tmdbIds
     */
    public function __construct(array $tmdbIds)
    {
        $this->tmdbIds = $tmdbIds;
    }

    /**
     * @return array<int>
     */
    public function getTmdbIds(): array
    {
        return $this->tmdbIds;
    }
}
