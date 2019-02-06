<?php

namespace App\Model\Tmdb;

use App\Annotation\Tmdb\TmdbType;
use App\Annotation\Tmdb\TmdbField;

/**
 * @TmdbType("GENRE")
 */
class Genre
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
    private $name;

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
}
