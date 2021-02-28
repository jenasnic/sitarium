<?php

namespace App\Entity\Maze;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="maze_casting_actor")
 * @ORM\Entity(repositoryClass="App\Repository\Maze\CastingActorRepository")
 */
class CastingActor
{
    /**
     * @ORM\Id
     * @ORM\Column(name="tmdbId", type="integer")
     */
    private int $tmdbId;

    /**
     * @ORM\Column(name="fullname", type="string", length=55)
     */
    private ?string $fullname = null;

    /**
     * @ORM\Column(name="pictureUrl", type="string", length=255, nullable=true)
     */
    private ?string $pictureUrl = null;

    /**
     * @ORM\ManyToMany(targetEntity="Movie", inversedBy="actors", cascade={"persist"})
     * @ORM\JoinTable(name="maze_movie_casting_actor",
     *     joinColumns={@ORM\JoinColumn(name="actor_id", referencedColumnName="tmdbId", onDelete="CASCADE")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="movie_id", referencedColumnName="tmdbId", onDelete="CASCADE")}
     * )
     *
     * @var Collection<int, Movie>
     */
    private Collection $movies;

    public function __construct()
    {
        $this->movies = new ArrayCollection();
    }

    public function getTmdbId(): int
    {
        return $this->tmdbId;
    }

    public function setTmdbId(int $tmdbId): void
    {
        $this->tmdbId = $tmdbId;
    }

    public function getFullname(): ?string
    {
        return $this->fullname;
    }

    public function setFullname(string $fullname): void
    {
        $this->fullname = $fullname;
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
     * @return Movie[]|Collection<int, Movie>
     */
    public function getMovies(): Collection
    {
        return $this->movies;
    }

    public function addMovie(Movie $movie): void
    {
        $this->movies->add($movie);
    }

    public function removeMovie(Movie $movie): void
    {
        $this->movies->removeElement($movie);
    }
}
