<?php

namespace App\Entity\Maze;

use App\Enum\Maze\FilmographyStatusEnum;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use InvalidArgumentException;

/**
 * @ORM\Table(name="maze_actor")
 * @ORM\Entity(repositoryClass="App\Repository\Maze\ActorRepository")
 */
class Actor
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
     * @ORM\Column(name="birthdate", type="datetime", nullable=true)
     */
    private ?DateTime $birthdate = null;

    /**
     * @ORM\Column(name="pictureUrl", type="string", length=255, nullable=true)
     */
    private ?string $pictureUrl = null;

    /**
     * @ORM\Column(name="status", type="string", length=25)
     */
    private string $status;

    /**
     * @ORM\ManyToMany(targetEntity="FilmographyMovie", mappedBy="actors")
     *
     * @var Collection<int, FilmographyMovie>
     */
    private Collection $movies;

    public function __construct()
    {
        $this->movies = new ArrayCollection();
        $this->setStatus(FilmographyStatusEnum::UNINITIALIZED);
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

    public function getBirthdate(): ?DateTime
    {
        return $this->birthdate;
    }

    public function setBirthdate(?DateTime $birthdate): void
    {
        $this->birthdate = $birthdate;
    }

    public function getPictureUrl(): ?string
    {
        return $this->pictureUrl;
    }

    public function setPictureUrl(?string $pictureUrl): void
    {
        $this->pictureUrl = $pictureUrl;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status): void
    {
        if (!FilmographyStatusEnum::exists($status)) {
            throw new InvalidArgumentException(sprintf('Invalid status "%s"', $status));
        }

        $this->status = $status;
    }

    /**
     * @return FilmographyMovie[]|Collection<int, FilmographyMovie>
     */
    public function getMovies(): Collection
    {
        return $this->movies;
    }

    public function addMovie(FilmographyMovie $movie): void
    {
        $this->movies->add($movie);
    }

    public function removeMovie(FilmographyMovie $movie): void
    {
        $this->movies->removeElement($movie);
    }
}
