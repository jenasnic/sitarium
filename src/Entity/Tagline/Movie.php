<?php

namespace App\Entity\Tagline;

use App\Annotation\Tmdb\TmdbField;
use App\Annotation\Tmdb\TmdbType;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Movie.
 *
 * @ORM\Table(name="tagline_movie")
 * @ORM\Entity(repositoryClass="App\Repository\Tagline\MovieRepository")
 * @TmdbType("MOVIE")
 */
class Movie
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
     * @ORM\Column(name="title", type="string", length=255)
     * @TmdbField(name="title")
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="pictureUrl", type="string", length=255, nullable=true)
     * @TmdbField(name="poster_path")
     */
    private $pictureUrl;

    /**
     * @var int
     * @TmdbField(name="vote_count", type="integer")
     */
    private $voteCount;

    /**
     * @var array
     *
     * @TmdbField(name="genre_ids", type="array")
     */
    private $genreIds;

    /**
     * @var Genre[]|Collection
     *
     * @ORM\ManyToMany(targetEntity="Genre")
     * @ORM\JoinTable(name="tagline_movie_genre",
     *      joinColumns={@ORM\JoinColumn(name="movie_id", referencedColumnName="tmdbId", onDelete="CASCADE")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="genre_id", referencedColumnName="tmdbId", onDelete="CASCADE")}
     * )
     */
    private $genres;

    public function __construct()
    {
        $this->genres = new ArrayCollection();
    }

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

    /**
     * @return int
     */
    public function getVoteCount(): int
    {
        return $this->voteCount;
    }

    /**
     * @param int $voteCount
     */
    public function setVoteCount(int $voteCount)
    {
        $this->voteCount = $voteCount;
    }

    /**
     * @return array
     */
    public function getGenreIds(): array
    {
        return $this->genreIds;
    }

    /**
     * @param array $genreIds
     */
    public function setGenreIds(array $genreIds)
    {
        $this->genreIds = $genreIds;
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
     *
     * @return self
     */
    public function addGenre(Genre $genre): self
    {
        $this->genres->add($genre);

        return $this;
    }

    /**
     * @param Genre $genre
     *
     * @return self
     */
    public function removeGenre(Genre $genre): self
    {
        $this->genres->removeElement($genre);

        return $this;
    }
}
