<?php

namespace App\Model\Tmdb;

class DisplayableItem
{
    public int $tmdbId;

    public string $displayName;

    public string $pictureUrl;

    public function __construct($tmdbId, $displayName, $pictureUrl)
    {
        $this->tmdbId = $tmdbId;
        $this->displayName = $displayName;
        $this->pictureUrl = $pictureUrl;
    }
}
