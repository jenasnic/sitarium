<?php

namespace App\Entity\Quiz;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * @ORM\Table(name="quiz")
 * @ORM\Entity(repositoryClass="App\Repository\Quiz\QuizRepository")
 */
class Quiz
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(name="id", type="integer")
     */
    private ?int $id = null;

    /**
     * @ORM\Column(name="name", type="string", length=255)
     */
    private ?string $name = null;

    /**
     * @ORM\Column(name="slug", type="string", length=128, unique=true)
     */
    private ?string $slug = null;

    /**
     * @ORM\Column(name="displayResponse", type="boolean")
     */
    private bool $displayResponse;

    /**
     * @ORM\Column(name="displayTrick", type="boolean")
     */
    private bool $displayTrick;

    /**
     * @ORM\Column(name="published", type="boolean")
     */
    private bool $published;

    /**
     * @ORM\Column(name="rank", type="integer")
     */
    private int $rank;

    /**
     * @ORM\Column(name="pictureUrl", type="text", nullable=true)
     */
    private ?string $pictureUrl = null;

    /**
     * @ORM\Column(name="pictureWidth", type="integer", nullable=true)
     */
    private ?int $pictureWidth = null;

    /**
     * @ORM\Column(name="pictureHeight", type="integer", nullable=true)
     */
    private ?int $pictureHeight = null;

    /**
     * @ORM\Column(name="thumbnailUrl", type="text", nullable=true)
     */
    private ?string $thumbnailUrl = null;

    /**
     * @ORM\OneToMany(targetEntity="Response", mappedBy="quiz", cascade={"remove"})
     * @ORM\OrderBy({"title": "ASC"})
     *
     * @var Collection<int, Response>
     */
    private Collection $responses;

    private ?UploadedFile $pictureFile = null;

    private ?UploadedFile $thumbnailFile = null;

    public function __construct()
    {
        $this->displayResponse = false;
        $this->displayTrick = false;
        $this->published = false;
        $this->rank = 0;

        $this->responses = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): void
    {
        $this->slug = $slug;
    }

    public function isDisplayResponse(): bool
    {
        return $this->displayResponse;
    }

    public function setDisplayResponse(bool $displayResponse): void
    {
        $this->displayResponse = $displayResponse;
    }

    public function isDisplayTrick(): bool
    {
        return $this->displayTrick;
    }

    public function setDisplayTrick(bool $displayTrick): void
    {
        $this->displayTrick = $displayTrick;
    }

    public function isPublished(): bool
    {
        return $this->published;
    }

    public function setPublished(bool $published): void
    {
        $this->published = $published;
    }

    public function getRank(): int
    {
        return $this->rank;
    }

    public function setRank(int $rank): void
    {
        $this->rank = $rank;
    }

    public function getPictureUrl(): ?string
    {
        return $this->pictureUrl;
    }

    public function setPictureUrl(?string $pictureUrl): void
    {
        $this->pictureUrl = $pictureUrl;
    }

    public function getPictureWidth(): ?int
    {
        return $this->pictureWidth;
    }

    public function setPictureWidth(int $pictureWidth): void
    {
        $this->pictureWidth = $pictureWidth;
    }

    public function getPictureHeight(): ?int
    {
        return $this->pictureHeight;
    }

    public function setPictureHeight(int $pictureHeight): void
    {
        $this->pictureHeight = $pictureHeight;
    }

    public function getThumbnailUrl(): ?string
    {
        return $this->thumbnailUrl;
    }

    public function setThumbnailUrl(?string $thumbnailUrl): void
    {
        $this->thumbnailUrl = $thumbnailUrl;
    }

    /**
     * @return Response[]|Collection<int, Response>
     */
    public function getResponses(): Collection
    {
        return $this->responses;
    }

    public function addResponse(Response $response): void
    {
        $this->responses->add($response);
    }

    public function removeResponse(Response $response): void
    {
        $this->responses->removeElement($response);
    }

    public function getPictureFile(): ?UploadedFile
    {
        return $this->pictureFile;
    }

    public function setPictureFile(?UploadedFile $pictureFile): void
    {
        $this->pictureFile = $pictureFile;
    }

    public function getThumbnailFile(): ?UploadedFile
    {
        return $this->thumbnailFile;
    }

    public function setThumbnailFile(?UploadedFile $thumbnailFile): void
    {
        $this->thumbnailFile = $thumbnailFile;
    }
}
