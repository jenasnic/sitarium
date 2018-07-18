<?php

namespace App\Model\Tmdb;

use App\Annotation\Tmdb\TmdbType;
use App\Annotation\Tmdb\TmdbField;

/**
 * @TmdbType("PERSON")
 */
class Actor
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
    private $name;

    /**
     * @TmdbField(name="birthday", type="datetime", dateFormat="Y-m-d")
     * @var \DateTime
     */
    private $birthdate;

    /**
     * @TmdbField(name="profile_path")
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
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name)
    {
        $this->name = $name;
    }

    /**
     * @return \DateTime
     */
    public function getBirthdate(): \DateTime
    {
        return $this->birthdate;
    }

    /**
     * @param \DateTime $birthdate
     */
    public function setBirthdate(\DateTime $birthdate)
    {
        $this->birthdate = $birthdate;
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
