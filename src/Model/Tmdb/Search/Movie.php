<?php

namespace App\Model\Tmdb\Search;

use App\Annotation\Tmdb\TmdbType;
use App\Annotation\Tmdb\TmdbField;

/**
 * @TmdbType("MOVIE")
 */
class Movie implements DisplayableInterface
{
    /**
     * @TmdbField(name="id", type="integer")
     *
     * @var int
     */
    private $tmdbId;

    /**
     * @TmdbField(name="title")
     *
     * @var string
     */
    private $title;

    /**
     * @TmdbField(name="poster_path")
     *
     * @var string
     */
    private $pictureUrl;

    /**
     * {@inheritdoc}
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
     * {@inheritdoc}
     */
    public function getPictureUrl(): ?string
    {
        return $this->pictureUrl;
    }

    /**
     * @param string|null $pictureUrl
     */
    public function setPictureUrl(?string $pictureUrl)
    {
        $this->pictureUrl = $pictureUrl;
    }

    /**
     * {@inheritdoc}
     */
    public function getDisplayName(): string
    {
        return $this->title;
    }
}
