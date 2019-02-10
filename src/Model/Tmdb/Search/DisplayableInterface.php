<?php

namespace App\Model\Tmdb\Search;

interface DisplayableInterface
{
    /**
     * @return int
     */
    public function getTmdbId(): int;

    /**
     * @return string
     */
    public function getDisplayName();

    /**
     * @return string|null
     */
    public function getPictureUrl();
}
