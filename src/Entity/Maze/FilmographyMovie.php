<?php

namespace App\Entity\Maze;

use App\Annotation\Tmdb\TmdbField;
use App\Model\Tmdb\Search\DisplayableInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="maze_filmography_movie")
 * @ORM\Entity(repositoryClass="App\Repository\Maze\FilmographyMovieRepository")
 */
class FilmographyMovie implements DisplayableInterface
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
     * @ORM\Column(name="title", type="string", length=255)
     * @TmdbField(name="title")
     *
     * @var string
     */
    private $title;

    /**
     * @ORM\Column(name="releaseDate", type="datetime", nullable=true)
     * @TmdbField(name="release_date", type="datetime", dateFormat="Y-m-d")
     *
     * @var \DateTime
     */
    private $releaseDate;

    /**
     * @ORM\Column(name="voteCount", type="integer")
     * @TmdbField(name="vote_count", type="integer")
     *
     * @var int
     */
    private $voteCount;

    /**
     * @ORM\Column(name="pictureUrl", type="string", length=255, nullable=true)
     * @TmdbField(name="poster_path")
     *
     * @var string
     */
    private $pictureUrl;

    /**
     * @TmdbField(name="character")
     *
     * @var string
     */
    private $character;

    /**
     * @TmdbField(name="genre_ids")
     *
     * @var array
     */
    private $genreIds;

    /**
     * @ORM\ManyToMany(targetEntity="Actor", inversedBy="movies", cascade={"persist"})
     * @ORM\JoinTable(name="maze_actor_filmography_movie",
     *      joinColumns={@ORM\JoinColumn(name="movie_id", referencedColumnName="tmdbId", onDelete="CASCADE")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="actor_id", referencedColumnName="tmdbId", onDelete="CASCADE")}
     * )
     *
     * @var Actor[]|Collection
     */
    private $actors;

    public function __construct()
    {
        $this->actors = new ArrayCollection();
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
     * @return Actor[]|Collection
     */
    public function getActors(): Collection
    {
        return $this->actors;
    }

    /**
     * @param Actor $actor
     */
    public function addActor(Actor $actor): void
    {
        $this->actors->add($actor);
    }

    /**
     * @param Actor $actor
     */
    public function removeActor(Actor $actor): void
    {
        $this->actors->removeElement($actor);
    }

    /**
     * {@inheritdoc}
     */
    public function getDisplayName(): string
    {
        return $this->title;
    }
}
