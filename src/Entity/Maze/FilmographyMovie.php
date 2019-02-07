<?php

namespace App\Entity\Maze;

use App\Annotation\Tmdb\TmdbField;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * FilmographyMovie.
 *
 * @ORM\Table(name="maze_filmography_movie")
 * @ORM\Entity(repositoryClass="App\Repository\Maze\FilmographyMovieRepository")
 */
class FilmographyMovie
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
     * @var \DateTime
     *
     * @ORM\Column(name="releaseDate", type="datetime", nullable=true)
     * @TmdbField(name="release_date", type="datetime", dateFormat="Y-m-d")
     */
    private $releaseDate;

    /**
     * @var int
     *
     * @ORM\Column(name="voteCount", type="integer")
     * @TmdbField(name="vote_count", type="integer")
     */
    private $voteCount;

    /**
     * @var string
     *
     * @ORM\Column(name="pictureUrl", type="string", length=255, nullable=true)
     * @TmdbField(name="poster_path")
     */
    private $pictureUrl;

    /**
     * @var string
     * @TmdbField(name="character")
     */
    private $character;

    /**
     * @var array
     * @TmdbField(name="genre_ids")
     */
    private $genreIds;

    /**
     * @var ArrayCollection
     * @ORM\ManyToMany(targetEntity="Actor", inversedBy="movies", cascade={"persist"})
     * @ORM\JoinTable(name="maze_actor_filmography_movie",
     *      joinColumns={@ORM\JoinColumn(name="movie_id", referencedColumnName="tmdbId", onDelete="CASCADE")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="actor_id", referencedColumnName="tmdbId", onDelete="CASCADE")}
     * )
     */
    private $actors;

    public function __construct()
    {
        $this->actors = new ArrayCollection();
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
     * @return \DateTime|null
     */
    public function getReleaseDate(): ?\DateTime
    {
        return $this->releaseDate;
    }

    /**
     * @param \DateTime|null $releaseDate
     */
    public function setReleaseDate(?\DateTime $releaseDate)
    {
        $this->releaseDate = $releaseDate;
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
     * @return string
     */
    public function getCharacter(): string
    {
        return $this->character;
    }

    /**
     * @param string $character
     */
    public function setCharacter(string $character)
    {
        $this->character = $character;
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
     * @return ArrayCollection
     */
    public function getActors(): ArrayCollection
    {
        return $this->actors;
    }

    /**
     * @param Actor $actor
     *
     * @return self
     */
    public function addActor(Actor $actor): self
    {
        $this->actors->add($actor);

        return $this;
    }

    /**
     * @param Actor $actor
     *
     * @return self
     */
    public function removeActor(Actor $actor): self
    {
        $this->actors->removeElement($actor);

        return $this;
    }
}
