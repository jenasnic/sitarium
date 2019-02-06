<?php

namespace App\Entity\Tagline;

use App\Annotation\Tmdb\TmdbField;
use App\Annotation\Tmdb\TmdbType;
use App\Model\Tmdb\Search\DisplayableInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="tagline_movie")
 * @ORM\Entity(repositoryClass="App\Repository\Tagline\MovieRepository")
 * @TmdbType("MOVIE")
 */
class Movie implements DisplayableInterface
{
    /**
     * @ORM\Id
     * @TmdbField(name="id", type="integer")
     * @ORM\Column(name="tmdbId", type="integer")
     *
     * @var int
     */
    private $tmdbId;

    /**
     * @ORM\Column(name="title", type="string", length=255)
     * @TmdbField(name="title")
     *
     * @var string
     */
    private $title;

    /**
     * @ORM\Column(name="pictureUrl", type="string", length=255, nullable=true)
     * @TmdbField(name="poster_path")
     *
     * @var string
     */
    private $pictureUrl;

    /**
     * @ORM\Column(name="tagline", type="text", nullable=true)
     * @TmdbField(name="tagline")
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
     * @TmdbField(name="vote_count", type="integer")
     *
     * @var int
     */
    private $voteCount;

    /**
     * @TmdbField(name="release_date", type="datetime", dateFormat="Y-m-d")
     *
     * @var \DateTime
     */
    private $releaseDate;

    /**
     * @TmdbField(name="genre_ids", type="array")
     *
     * @var array
     */
    private $genreIds;

    /**
     * @TmdbField(name="genres", type="object_array", subClass="App\Entity\Tagline\Genre")
     *
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

    /**
     * {@inheritdoc}
     */
    public function getDisplayName(): string
    {
        return $this->title;
    }
}
