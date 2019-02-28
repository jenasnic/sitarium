<?php

namespace App\Entity\Maze;

use App\Annotation\Tmdb\TmdbField;
use App\Model\Tmdb\Search\DisplayableInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="maze_casting_actor")
 * @ORM\Entity(repositoryClass="App\Repository\Maze\CastingActorRepository")
 */
class CastingActor implements DisplayableInterface
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
     * @ORM\Column(name="fullname", type="string", length=55)
     * @TmdbField(name="name")
     *
     * @var string
     */
    private $fullname;

    /**
     * @ORM\Column(name="pictureUrl", type="string", length=255, nullable=true)
     * @TmdbField(name="profile_path")
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
     * @ORM\ManyToMany(targetEntity="Movie", inversedBy="actors", cascade={"persist"})
     * @ORM\JoinTable(name="maze_movie_casting_actor",
     *      joinColumns={@ORM\JoinColumn(name="actor_id", referencedColumnName="tmdbId", onDelete="CASCADE")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="movie_id", referencedColumnName="tmdbId", onDelete="CASCADE")}
     * )
     *
     * @var Movie[]|Collection
     */
    private $movies;

    public function __construct()
    {
        $this->movies = new ArrayCollection();
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
    public function getFullname(): string
    {
        return $this->fullname;
    }

    /**
     * @param string $fullname
     */
    public function setFullname(string $fullname)
    {
        $this->fullname = $fullname;
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
     * @return Movie[]|Collection
     */
    public function getMovies(): Collection
    {
        return $this->movies;
    }

    /**
     * @param Movie $movie
     */
    public function addMovie(Movie $movie): void
    {
        $this->movies->add($movie);
    }

    /**
     * @param Movie $movie
     */
    public function removeMovie(Movie $movie): void
    {
        $this->movies->removeElement($movie);
    }

    /**
     * {@inheritdoc}
     */
    public function getDisplayName(): string
    {
        return $this->fullname;
    }
}
