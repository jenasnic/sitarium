<?php

namespace App\Entity\Maze;

use App\Enum\Maze\FilmographyStatusEnum;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="maze_actor")
 * @ORM\Entity(repositoryClass="App\Repository\Maze\ActorRepository")
 */
class Actor
{
    /**
     * @ORM\Id
     * @ORM\Column(name="tmdbId", type="integer")
     *
     * @var int
     */
    private $tmdbId;

    /**
     * @ORM\Column(name="fullname", type="string", length=55)
     *
     * @var string
     */
    private $fullname;

    /**
     * @ORM\Column(name="birthdate", type="datetime", nullable=true)
     *
     * @var \DateTime
     */
    private $birthdate;

    /**
     * @ORM\Column(name="pictureUrl", type="string", length=255, nullable=true)
     *
     * @var string
     */
    private $pictureUrl;

    /**
     * @ORM\Column(name="status", type="string", length=25)
     *
     * @var string
     */
    private $status;

    /**
     * @ORM\ManyToMany(targetEntity="FilmographyMovie", mappedBy="actors")
     *
     * @var FilmographyMovie[]|Collection
     */
    private $movies;

    public function __construct()
    {
        $this->movies = new ArrayCollection();
        $this->setStatus(FilmographyStatusEnum::UNINITIALIZED);
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
     * @return \DateTime|null
     */
    public function getBirthdate(): ?\DateTime
    {
        return $this->birthdate;
    }

    /**
     * @param \DateTime|null $birthdate
     */
    public function setBirthdate(?\DateTime $birthdate)
    {
        $this->birthdate = $birthdate;
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
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * @param string $status
     */
    public function setStatus(string $status)
    {
        if (!FilmographyStatusEnum::exists($status)) {
            throw new \InvalidArgumentException(sprintf('Invalid status "%s"', $status));
        }

        $this->status = $status;
    }

    /**
     * @return FilmographyMovie[]|Collection
     */
    public function getMovies(): Collection
    {
        return $this->movies;
    }

    /**
     * @param FilmographyMovie $movie
     */
    public function addMovie(FilmographyMovie $movie): void
    {
        $this->movies->add($movie);
    }

    /**
     * @param FilmographyMovie $movie
     */
    public function removeMovie(FilmographyMovie $movie): void
    {
        $this->movies->removeElement($movie);
    }
}
