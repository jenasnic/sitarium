<?php

namespace App\Entity\Maze;

use App\Enum\Maze\CastingStatusEnum;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="maze_movie")
 * @ORM\Entity(repositoryClass="App\Repository\Maze\MovieRepository")
 */
class Movie
{
    /**
     * @ORM\Id
     * @ORM\Column(name="tmdbId", type="integer")
     *
     * @var int
     */
    private $tmdbId;

    /**
     * @ORM\Column(name="title", type="string", length=255)
     *
     * @var string
     */
    private $title;

    /**
     * @ORM\Column(name="releaseDate", type="datetime", nullable=true)
     *
     * @var \DateTime
     */
    private $releaseDate;

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
     * @ORM\ManyToMany(targetEntity="CastingActor", mappedBy="movies")
     *
     * @var CastingActor[]|Collection
     */
    private $actors;

    public function __construct()
    {
        $this->actors = new ArrayCollection();
        $this->setStatus(CastingStatusEnum::UNINITIALIZED);
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
}
