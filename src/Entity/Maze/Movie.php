<?php

namespace App\Entity\Maze;

use App\Annotation\Tmdb\TmdbField;
use App\Annotation\Tmdb\TmdbType;
use App\Enum\Maze\CastingStatus;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Movie
 *
 * @ORM\Table(name="movie")
 * @ORM\Entity(repositoryClass="App\Repository\Maze\MovieRepository")
 * @TmdbType("MOVIE")
 */
class Movie
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
     * columnDefinition="ENUM('casting_to_check', 'casting_validated', 'casting_empty')")
     */
    private $status;

    /**
     * @var int
     * @TmdbField(name="vote_count", type="integer")
     */
    private $voteCount;

    /**
     * @var array
     * @TmdbField(name="genre_ids")
     */
    private $genreIds;

    /**
     * @var ArrayCollection $actors
     * @ORM\ManyToMany(targetEntity="CastingActor", mappedBy="movies")
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
        if (!CastingStatus::exists($status))
            throw new \InvalidArgumentException("Invalid status");

        $this->status = $status;
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
     * @return array
     */
    public function getGenreIds(): array
    {
        return $this->genreIds;
    }

    /**
     * @param string $genreIds
     */
    public function setGenreIds(string $genreIds)
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
     * @param CastingActor $actor
     *
     * @return self
     */
    public function addActor(CastingActor $actor): self
    {
        $this->actors->add($actor);

        return $this;
    }

    /**
     * @param CastingActor $actor
     *
     * @return self
     */
    public function removeActor(CastingActor $actor): self
    {
        $this->actors->removeElement($actor);

        return $this;
    }
}
