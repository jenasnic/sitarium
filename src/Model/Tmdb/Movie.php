<?php

namespace App\Model\Tmdb;

use App\Annotation\Tmdb\TmdbType;
use App\Annotation\Tmdb\TmdbField;

/**
 * @TmdbType("MOVIE")
 */
class Movie
{
    /**
     * @TmdbField(name="id", type="integer")
     * @var int
     */
    private $tmdbId;

    /**
     * @TmdbField(name="title")
     * @var string
     */
    private $title;

    /**
     * @TmdbField(name="release_date", type="datetime", dateFormat="Y-m-d")
     * @var \DateTime
     */
    private $releaseDate;

    /**
     * @TmdbField(name="poster_path")
     * @var string
     */
    private $pictureUrl;

    /**
     * @TmdbField(name="belongs_to_collection", type="object", subClass="App\Model\Tmdb\MovieCollection")
     * @var MovieCollection
     */
    private $collection;

    /**
     * @TmdbField(name="tagline")
     * @var string
     */
    private $tagline;

    /**
     * @TmdbField(name="overview")
     * @var string
     */
    private $overview;

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
     * @return \DateTime
     */
    public function getReleaseDate(): \DateTime
    {
        return $this->releaseDate;
    }

    /**
     * @param \DateTime $releaseDate
     */
    public function setReleaseDate(\DateTime $releaseDate)
    {
        $this->releaseDate = $releaseDate;
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

    /**
     * @return MovieCollection
     */
    public function getCollection(): MovieCollection
    {
        return $this->collection;
    }

    /**
     * @param MovieCollection $collection
     */
    public function setCollection(MovieCollection $collection)
    {
        $this->collection = $collection;
    }

    /**
     * @return string
     */
    public function getTagline(): string
    {
        return $this->tagline;
    }

    /**
     * @param string $tagline
     */
    public function setTagline(string $tagline)
    {
        $this->tagline = $tagline;
    }

    /**
     * @return string
     */
    public function getOverview(): string
    {
        return $this->overview;
    }

    /**
     * @param string $overview
     */
    public function setOverview(string $overview)
    {
        $this->overview = $overview;
    }
}
