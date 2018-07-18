<?php

namespace App\Entity\Maze;

use App\Annotation\Tmdb\TmdbField;
use App\Annotation\Tmdb\TmdbType;
use App\Enum\Maze\FilmographyStatus;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Actor
 *
 * @ORM\Table(name="actor")
 * @ORM\Entity(repositoryClass="App\Repository\Maze\ActorRepository")
 * @TmdbType("PERSON")
 */
class Actor
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
     * @var \DateTime
     *
     * @ORM\Column(name="birthdate", type="datetime", nullable=true)
     * @TmdbField(name="birthday", type="datetime", dateFormat="Y-m-d")
     */
    private $birthdate;

    /**
     * @var string
     *
     * @ORM\Column(name="pictureUrl", type="string", length=255, nullable=true)
     * @TmdbField(name="profile_path")
     */
    private $pictureUrl;

    /**
     * @var string
     *
     * @ORM\Column(name="status", type="string", length=25,
     * columnDefinition="ENUM('filmography_to_check', 'filmography_validated', 'filmography_empty')")
     */
    private $status;

    /**
     * @var ArrayCollection $movies
     * @ORM\ManyToMany(targetEntity="FilmographyMovie", mappedBy="actors")
     */
    private $movies;

    public function __construct()
    {
        $this->movies = new ArrayCollection();
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
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * @param string $status
     */
    public function setStatus(string $status)
    {
        if (!FilmographyStatus::exists($status))
            throw new \InvalidArgumentException("Invalid status");

        $this->status = $status;
    }

    /**
     * @return ArrayCollection
     */
    public function getMovies(): ArrayCollection
    {
        return $this->movies;
    }

    /**
     * @param FilmographyMovie $movie
     *
     * @return self
     */
    public function addMovie(FilmographyMovie $movie): self
    {
        $this->movies->add($movie);

        return $this;
    }

    /**
     * @param FilmographyMovie $movie
     *
     * @return self
     */
    public function removeMovie(FilmographyMovie $movie): self
    {
        $this->movies->removeElement($movie);

        return $this;
    }
}
