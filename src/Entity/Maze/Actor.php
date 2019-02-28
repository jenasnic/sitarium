<?php

namespace App\Entity\Maze;

use App\Annotation\Tmdb\TmdbField;
use App\Annotation\Tmdb\TmdbType;
use App\Enum\Maze\FilmographyStatus;
use App\Model\Tmdb\Search\DisplayableInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="maze_actor")
 * @ORM\Entity(repositoryClass="App\Repository\Maze\ActorRepository")
 * @TmdbType("PERSON")
 */
class Actor implements DisplayableInterface
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
     * @ORM\Column(name="birthdate", type="datetime", nullable=true)
     * @TmdbField(name="birthday", type="datetime", dateFormat="Y-m-d")
     *
     * @var \DateTime
     */
    private $birthdate;

    /**
     * @ORM\Column(name="pictureUrl", type="string", length=255, nullable=true)
     * @TmdbField(name="profile_path")
     *
     * @var string
     */
    private $pictureUrl;

    /**
     * @ORM\Column(name="status", type="string", length=25,
     *      columnDefinition="ENUM('filmography_to_check', 'filmography_validated', 'filmography_empty')")
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
        if (!FilmographyStatus::exists($status)) {
            throw new \InvalidArgumentException('Invalid status');
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

    /**
     * {@inheritdoc}
     */
    public function getDisplayName(): string
    {
        return $this->fullname;
    }
}
