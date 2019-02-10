<?php

namespace App\Model\Tmdb;

use App\Annotation\Tmdb\TmdbField;

class TvSeason
{
    /**
     * @TmdbField(name="id", type="integer")
     *
     * @var int
     */
    private $tmdbId;

    /**
     * @TmdbField(name="episode_count", type="integer")
     *
     * @var int
     */
    private $episodeCount;

    /**
     * @TmdbField(name="air_date", type="datetime", dateFormat="Y-m-d")
     *
     * @var \DateTime
     */
    private $airingDate;

    /**
     * @TmdbField(name="poster_path")
     *
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
     * @return int
     */
    public function getEpisodeCount(): int
    {
        return $this->episodeCount;
    }

    /**
     * @param int $episodeCount
     */
    public function setEpisodeCount(int $episodeCount)
    {
        $this->episodeCount = $episodeCount;
    }

    /**
     * @return \DateTime|null
     */
    public function getAiringDate(): ?\DateTime
    {
        return $this->airingDate;
    }

    /**
     * @param \DateTime|null $airingDate
     */
    public function setAiringDate(?\DateTime $airingDate)
    {
        $this->airingDate = $airingDate;
    }

    /**
     * @return string|null
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
}
