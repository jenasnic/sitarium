<?php

namespace App\Entity\Tagline;

use App\Annotation\Tmdb\TmdbField;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Table(name="tagline_genre")
 * @ORM\Entity(repositoryClass="App\Repository\Tagline\GenreRepository")
 */
class Genre
{
    /**
     * @ORM\Id
     * @ORM\Column(name="tmdbId", type="integer")
     * @TmdbField(name="id", type="integer")
     *
     * @var int
     */
    private $tmdbId;

    /**
     * @ORM\Column(name="name", type="string", length=55)
     * @TmdbField(name="name")
     *
     * @var string
     */
    private $name;

    /**
     * @ORM\Column(name="slug", type="string", length=128, unique=true)
     * @Gedmo\Slug(fields={"name"})
     *
     * @var string
     */
    private $slug;

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
     * @return string
     */
    public function getSlug(): string
    {
        return $this->slug;
    }

    /**
     * @param string $slug
     */
    public function setSlug(string $slug)
    {
        $this->slug = $slug;
    }
}
