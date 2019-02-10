<?php

namespace App\Entity\Maze;

use App\Annotation\Tmdb\TmdbField;
use App\Model\Tmdb\Search\DisplayableInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * CastingActor.
 *
 * @ORM\Table(name="maze_casting_actor")
 * @ORM\Entity(repositoryClass="App\Repository\Maze\CastingActorRepository")
 */
class CastingActor implements DisplayableInterface
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
     * @ORM\Column(name="fullname", type="string", length=55)
     * @TmdbField(name="name")
     */
    private $fullname;

    /**
     * @var string
     *
     * @ORM\Column(name="pictureUrl", type="string", length=255, nullable=true)
     * @TmdbField(name="profile_path")
     */
    private $pictureUrl;

    /**
     * @var string
     * @TmdbField(name="character")
     */
    private $character;

    /**
     * @var ArrayCollection
     * @ORM\ManyToMany(targetEntity="Movie", inversedBy="actors", cascade={"persist"})
     * @ORM\JoinTable(name="maze_movie_casting_actor",
     *      joinColumns={@ORM\JoinColumn(name="actor_id", referencedColumnName="tmdbId", onDelete="CASCADE")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="movie_id", referencedColumnName="tmdbId", onDelete="CASCADE")}
     * )
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
     * @return ArrayCollection
     */
    public function getMovies(): ArrayCollection
    {
        return $this->movies;
    }

    /**
     * @param Movie $movie
     *
     * @return self
     */
    public function addMovie(Movie $movie): self
    {
        $this->movies->add($movie);

        return $this;
    }

    /**
     * @param Movie $movie
     *
     * @return self
     */
    public function removeMovie(Movie $movie): self
    {
        $this->movies->removeElement($movie);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getDisplayName(): string
    {
        return $this->fullname;
    }
}
