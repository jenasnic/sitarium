<?php

namespace App\Entity\Tagline;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="tagline_movie")
 * @ORM\Entity(repositoryClass="App\Repository\Tagline\MovieRepository")
 */
class Movie
{
    /**
     * @ORM\Id
     * @ORM\Column(name="tmdbId", type="integer")
     */
    private ?int $tmdbId = null;

    /**
     * @ORM\Column(name="title", type="string", length=255)
     */
    private ?string $title = null;

    /**
     * @ORM\Column(name="releaseDate", type="datetime", nullable=true)
     */
    private ?DateTime $releaseDate = null;

    /**
     * @ORM\Column(name="pictureUrl", type="string", length=255, nullable=true)
     */
    private ?string $pictureUrl = null;

    /**
     * @ORM\Column(name="tagline", type="text", nullable=true)
     */
    private ?string $tagline = null;

    /**
     * @ORM\ManyToMany(targetEntity="Genre")
     * @ORM\JoinTable(name="tagline_movie_genre",
     *      joinColumns={@ORM\JoinColumn(name="movie_id", referencedColumnName="tmdbId", onDelete="CASCADE")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="genre_id", referencedColumnName="tmdbId", onDelete="CASCADE")}
     * )
     *
     * @var Genre[]|Collection<int, Genre>
     */
    private Collection $genres;

    public function __construct()
    {
        $this->genres = new ArrayCollection();
    }

    public function getTmdbId(): ?int
    {
        return $this->tmdbId;
    }

    public function setTmdbId(int $tmdbId): void
    {
        $this->tmdbId = $tmdbId;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getReleaseDate(): ?DateTime
    {
        return $this->releaseDate;
    }

    public function setReleaseDate(?DateTime $releaseDate): void
    {
        $this->releaseDate = $releaseDate;
    }

    public function getPictureUrl(): ?string
    {
        return $this->pictureUrl;
    }

    public function setPictureUrl(?string $pictureUrl): void
    {
        $this->pictureUrl = $pictureUrl;
    }

    public function getTagline(): ?string
    {
        return $this->tagline;
    }

    public function setTagline(?string $tagline): void
    {
        $this->tagline = $tagline;
    }

    /**
     * @return Genre[]|Collection<int, Genre>
     */
    public function getGenres(): Collection
    {
        return $this->genres;
    }

    public function addGenre(Genre $genre): void
    {
        $this->genres->add($genre);
    }

    public function removeGenre(Genre $genre): void
    {
        $this->genres->removeElement($genre);
    }
}
