<?php

namespace App\Entity\Maze;

use App\Enum\Maze\CastingStatusEnum;
use DateTime;
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
     */
    private int $tmdbId;

    /**
     * @ORM\Column(name="title", type="string", length=255)
     */
    private ?string $title = null;

    /**
     * @ORM\Column(name="releaseDate", type="datetime", nullable=true)
     */
    private ?DateTime $releaseDate = null;

    /**
     * @ORM\Column(name="pictureUrl", type="string", length=255, nullable=true)
     *
     * @var 
     */
    private ?string $pictureUrl = null;

    /**
     * @ORM\Column(name="status", type="string", length=25)
     */
    private string $status;

    /**
     * @ORM\ManyToMany(targetEntity="CastingActor", mappedBy="movies")
     *
     * @var Collection<int, CastingActor>
     */
    private Collection $actors;

    public function __construct()
    {
        $this->actors = new ArrayCollection();
        $this->setStatus(CastingStatusEnum::UNINITIALIZED);
    }

    public function getTmdbId(): int
    {
        return $this->tmdbId;
    }

    public function setTmdbId(int $tmdbId): void
    {
        $this->tmdbId = $tmdbId;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getReleaseDate(): ?DateTime
    {
        return $this->releaseDate;
    }

    public function setReleaseDate(?DateTime $releaseDate): void
    {
        $this->releaseDate = $releaseDate;
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
        if (!CastingStatusEnum::exists($status)) {
            throw new \InvalidArgumentException(sprintf('Invalid status "%s"', $status));
        }

        $this->status = $status;
    }

    /**
     * @return CastingActor[]|Collection<int, CastingActor>
     */
    public function getActors(): Collection
    {
        return $this->actors;
    }

    public function addActor(CastingActor $actor): void
    {
        $this->actors->add($actor);
    }

    public function removeActor(CastingActor $actor): void
    {
        $this->actors->removeElement($actor);
    }
}
