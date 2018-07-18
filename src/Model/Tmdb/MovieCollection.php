<?php

namespace App\Model\Tmdb;

use App\Annotation\Tmdb\TmdbField;

class MovieCollection
{
    /**
     * @TmdbField(name="id", type="integer")
     * @var int
     */
    private $tmdbId;

    /**
     * @TmdbField(name="name")
     * @var string
     */
    private $title;

    /**
     * @TmdbField(name="poster_path")
     * @var string
     */
    private $pictureUrl;

    /**
     * @return int
     */
    public function getTmdbId(): int
    {
        return $this->tmdbId;
    }

    /**
     * @param int $tmdbId
     */
    public function setTmdbId(int $tmdbId)
    {
        $this->tmdbId = $tmdbId;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle(string $title)
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getPictureUrl(): string
    {
        return $this->pictureUrl;
    }

    /**
     * @param string $pictureUrl
     */
    public function setPictureUrl(string $pictureUrl)
    {
        $this->pictureUrl = $pictureUrl;
    }
}
