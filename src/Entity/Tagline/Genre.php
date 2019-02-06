<?php

namespace App\Entity\Tagline;

use App\Annotation\Tmdb\TmdbField;
use Doctrine\ORM\Mapping as ORM;

/**
 * Genre.
 *
 * @ORM\Table(name="tagline_genre")
 * @ORM\Entity(repositoryClass="App\Repository\Tagline\GenreRepository")
 */
class Genre
{
    /**
     * @var int
     *
     * @ORM\Column(name="tmdbId", type="integer")
     * @ORM\Id
     * @TmdbField(name="id", type="integer")
     */
    private $tmdbId;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=55)
     * @TmdbField(name="name")
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
