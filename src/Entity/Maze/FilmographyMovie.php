<?php

namespace App\Entity\Maze;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="maze_filmography_movie")
 * @ORM\Entity(repositoryClass="App\Repository\Maze\FilmographyMovieRepository")
 */
class FilmographyMovie
{
    /**
     * @ORM\Id
     * @ORM\Column(name="tmdbId", type="integer")
     */
    private int $tmdbId;

    /**
     * @ORM\Column(name="title", type="string", length=255)
     */
    private ?string $title = null;

    /**
     * @ORM\Column(name="releaseDate", type="datetime", nullable=true)
     */
    private ?DateTime $releaseDate = null;

    /**
     * @ORM\Column(name="voteCount", type="integer")
     */
    private int $voteCount = 0;

    /**
     * @ORM\Column(name="pictureUrl", type="string", length=255, nullable=true)
     */
    private ?string $pictureUrl = null;

    /**
     * @ORM\ManyToMany(targetEntity="Actor", inversedBy="movies", cascade={"persist"})
     * @ORM\JoinTable(name="maze_actor_filmography_movie",
     *     joinColumns={@ORM\JoinColumn(name="movie_id", referencedColumnName="tmdbId", onDelete="CASCADE")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="actor_id", referencedColumnName="tmdbId", onDelete="CASCADE")}
     * )
     *
     * @var Collection<int, Actor>
     */
    private Collection $actors;

    public function __construct()
    {
        $this->actors = new ArrayCollection();
    }

    public function getTmdbId(): int
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

    public function getVoteCount(): int
    {
        return $this->voteCount;
    }

    public function setVoteCount(int $voteCount): void
    {
        $this->voteCount = $voteCount;
    }

    public function getPictureUrl(): ?string
    {
        return $this->pictureUrl;
    }

    public function setPictureUrl(?string $pictureUrl): void
    {
        $this->pictureUrl = $pictureUrl;
    }

    /**
     * @return Actor[]|Collection<int, Actor>
     */
    public function getActors(): Collection
    {
        return $this->actors;
    }

    public function addActor(Actor $actor): void
    {
        $this->actors->add($actor);
    }

    public function removeActor(Actor $actor): void
    {
        $this->actors->removeElement($actor);
    }
}
