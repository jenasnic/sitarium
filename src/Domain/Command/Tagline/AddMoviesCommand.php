<?php

namespace App\Domain\Command\Tagline;

class AddMoviesCommand
{
    protected array $tmdbIds;

    public function __construct(array $tmdbIds)
    {
        $this->tmdbIds = $tmdbIds;
    }

    public function getTmdbIds(): array
    {
        return $this->tmdbIds;
    }
}
