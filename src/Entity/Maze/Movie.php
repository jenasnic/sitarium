<?php

namespace App\Entity\Maze;

use App\Annotation\Tmdb\TmdbField;
use App\Annotation\Tmdb\TmdbType;
use App\Enum\Maze\CastingStatusEnum;
use App\Model\Tmdb\Search\DisplayableInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="maze_movie")
 * @ORM\Entity(repositoryClass="App\Repository\Maze\MovieRepository")
 * @TmdbType("MOVIE")
 */
class Movie implements DisplayableInterface
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
     * @ORM\Column(name="pictureUrl", type="string", length=255, nullable=true)
     * @TmdbField(name="poster_path")
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
     * @TmdbField(name="vote_count", type="integer")
     *
     * @var int
     */
    private $voteCount;

    /**
     * @TmdbField(name="genre_ids")
     *
     * @var array
     */
    private $genreIds;

    /**
     * @ORM\ManyToMany(targetEntity="CastingActor", mappedBy="movies")
     *
     * @var CastingActor[]|Collection
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
        if (!CastingStatusEnum::exists($status)) {
            throw new \InvalidArgumentException(sprintf('Invalid status "%s"', $status));
        }

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
     * @return CastingActor[]|Collection
     */
    public function getActors(): Collection
    {
        return $this->actors;
    }

    /**
     * @param CastingActor $actor
     */
    public function addActor(CastingActor $actor): void
    {
        $this->actors->add($actor);
    }

    /**
     * @param CastingActor $actor
     */
    public function removeActor(CastingActor $actor): void
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
