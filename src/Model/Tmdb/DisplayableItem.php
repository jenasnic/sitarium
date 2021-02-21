<?php

namespace App\Model\Tmdb;

class DisplayableItem
{
    /**
     * @var int
     */
    public $tmdbId;

    /**
     * @var string
     */
    public $displayName;

    /**
     * @var string
     */

    public $pictureUrl;

    /**
     * @param int $tmdbId
     * @param string $displayName
     * @param string $pictureUrl
     */
    public function __construct($tmdbId, $displayName, $pictureUrl)
    {
        $this->tmdbId = $tmdbId;
        $this->displayName = $displayName;
        $this->pictureUrl = $pictureUrl;
    }
}
