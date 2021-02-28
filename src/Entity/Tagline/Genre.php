<?php

namespace App\Entity\Tagline;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="tagline_genre")
 * @ORM\Entity(repositoryClass="App\Repository\Tagline\GenreRepository")
 */
class Genre
{
    /**
     * @ORM\Id
     * @ORM\Column(name="tmdbId", type="integer")
     */
    private ?int $tmdbId = null;

    /**
     * @ORM\Column(name="name", type="string", length=55)
     */
    private ?string $name = null;

    /**
     * @ORM\Column(name="slug", type="string", length=128, unique=true)
     */
    private ?string $slug = null;

    public function getTmdbId(): ?int
    {
        return $this->tmdbId;
    }

    public function setTmdbId(int $tmdbId): void
    {
        $this->tmdbId = $tmdbId;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): void
    {
        $this->slug = $slug;
    }
}
