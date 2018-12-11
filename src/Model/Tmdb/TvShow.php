<?php

namespace App\Model\Tmdb;

use App\Annotation\Tmdb\TmdbType;
use App\Annotation\Tmdb\TmdbField;

/**
 * @TmdbType("TV")
 */
class TvShow
{
    /**
     * @TmdbField(name="id", type="integer")
     *
     * @var int
     */
    private $tmdbId;

    /**
     * @TmdbField(name="name")
     *
     * @var string
     */
    private $title;

    /**
     * @TmdbField(name="last_air_date", type="datetime", dateFormat="Y-m-d")
     *
     * @var \DateTime
     */
    private $lastAiringDate;

    /**
     * @TmdbField(name="poster_path")
     *
     * @var string
     */
    private $pictureUrl;

    /**
     * @TmdbField(name="seasons", type="array", subClass="App\Model\Tmdb\TvSeason")
     *
     * @var array
     */
    private $seasonList;

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
    public function getLastAiringDate()
    {
        return $this->lastAiringDate;
    }

    /**
     * @param \DateTime $lastAiringDate
     */
    public function setLastAiringDate(\DateTime $lastAiringDate)
    {
        $this->lastAiringDate = $lastAiringDate;
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
     * @return array
     */
    public function getSeasonList(): array
    {
        return $this->seasonList;
    }

    /**
     * @param array $seasonList
     */
    public function setSeasonList(array $seasonList)
    {
        $this->seasonList = $seasonList;
    }
}
