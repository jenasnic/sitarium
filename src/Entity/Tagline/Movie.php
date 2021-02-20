<?php

namespace App\Entity\Tagline;

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
     *
     * @var int
     */
    private $tmdbId;

    /**
     * @ORM\Column(name="title", type="string", length=255)
     *
     * @var string
     */
    private $title;

    /**
     * @ORM\Column(name="pictureUrl", type="string", length=255, nullable=true)
     *
     * @var string
     */
    private $pictureUrl;

    /**
     * @ORM\Column(name="tagline", type="text", nullable=true)
     *
     * @var string
     */
    private $tagline;

    /**
     * @ORM\ManyToMany(targetEntity="Genre")
     * @ORM\JoinTable(name="tagline_movie_genre",
     *      joinColumns={@ORM\JoinColumn(name="movie_id", referencedColumnName="tmdbId", onDelete="CASCADE")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="genre_id", referencedColumnName="tmdbId", onDelete="CASCADE")}
     * )
     *
     * @var Genre[]|Collection
     */
    private $genres;

    /**
     * @var int
     */
    private $voteCount;

    /**
     * @var \DateTime
     */
    private $releaseDate;

    /**
     * @var array
     */
    private $genreIds;

    /**
     * @var Genre[]|array
     */
    private $tmdbGenres;

    public function __construct()
    {
        $this->genres = new ArrayCollection();
    }

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
     * @return string|null
     */
    public function getTagline(): ?string
    {
        return $this->tagline;
    }

    /**
     * @param string|null $tagline
     */
    public function setTagline(?string $tagline)
    {
        $this->tagline = $tagline;
    }

    /**
     * @return Genre[]|Collection
     */
    public function getGenres(): Collection
    {
        return $this->genres;
    }

    /**
     * @param Genre $genre
     */
    public function addGenre(Genre $genre): void
    {
        $this->genres->add($genre);
    }

    /**
     * @param Genre $genre
     */
    public function removeGenre(Genre $genre): void
    {
        $this->genres->removeElement($genre);
    }

    /**
     * @return int
     */
    public function getVoteCount(): int
    {
        return $this->voteCount;
    }

    /**
     * @return \DateTime|null
     */
    public function getReleaseDate(): ?\DateTime
    {
        return $this->releaseDate;
    }

    /**
     * @return array
     */
    public function getGenreIds(): array
    {
        return $this->genreIds;
    }

    /**
     * @return Genre[]|array
     */
    public function getTmdbGenres(): array
    {
        return $this->tmdbGenres;
    }
}
