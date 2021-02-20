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
     *
     * @var int
     */
    private $id;

    /**
     * @ORM\Column(name="name", type="string", length=255)
     *
     * @var string
     */
    private $name;

    /**
     * @ORM\Column(name="slug", type="string", length=128, unique=true)
     *
     * @var string
     */
    private $slug;

    /**
     * @ORM\Column(name="displayResponse", type="boolean")
     *
     * @var bool
     */
    private $displayResponse;

    /**
     * @ORM\Column(name="displayTrick", type="boolean")
     *
     * @var bool
     */
    private $displayTrick;

    /**
     * @ORM\Column(name="published", type="boolean")
     *
     * @var bool
     */
    private $published;

    /**
     * @ORM\Column(name="rank", type="integer")
     *
     * @var int
     */
    private $rank;

    /**
     * @ORM\Column(name="pictureUrl", type="text", nullable=true)
     *
     * @var string
     */
    private $pictureUrl;

    /**
     * @ORM\Column(name="pictureWidth", type="integer", nullable=true)
     *
     * @var int
     */
    private $pictureWidth;

    /**
     * @ORM\Column(name="pictureHeight", type="integer", nullable=true)
     *
     * @var int
     */
    private $pictureHeight;

    /**
     * @ORM\Column(name="thumbnailUrl", type="text", nullable=true)
     *
     * @var string
     */
    private $thumbnailUrl;

    /**
     * @ORM\OneToMany(targetEntity="Response", mappedBy="quiz", cascade={"remove"})
     * @ORM\OrderBy({"title" = "ASC"})
     *
     * @var Collection
     */
    private $responses;

    /**
     * Additional property used to upload file for picture.
     */
    private $pictureFile;

    /**
     * Additional property used to upload file for thumbnail.
     */
    private $thumbnailFile;

    public function __construct()
    {
        $this->displayResponse = false;
        $this->displayTrick = false;
        $this->published = false;

        $this->responses = new ArrayCollection();
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getSlug(): string
    {
        return $this->slug;
    }

    /**
     * @param string $slug
     */
    public function setSlug(string $slug)
    {
        $this->slug = $slug;
    }

    /**
     * @return bool
     */
    public function isDisplayResponse(): bool
    {
        return $this->displayResponse;
    }

    /**
     * @param bool $displayResponse
     */
    public function setDisplayResponse(bool $displayResponse)
    {
        $this->displayResponse = $displayResponse;
    }

    /**
     * @return bool
     */
    public function isDisplayTrick(): bool
    {
        return $this->displayTrick;
    }

    /**
     * @param bool $displayTrick
     */
    public function setDisplayTrick(bool $displayTrick)
    {
        $this->displayTrick = $displayTrick;
    }

    /**
     * @return bool
     */
    public function isPublished(): bool
    {
        return $this->published;
    }

    /**
     * @param bool $published
     */
    public function setPublished(bool $published)
    {
        $this->published = $published;
    }

    /**
     * @return int
     */
    public function getRank(): int
    {
        return $this->rank;
    }

    /**
     * @param int $rank
     */
    public function setRank(int $rank)
    {
        $this->rank = $rank;
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
     * @return int|null
     */
    public function getPictureWidth(): ?int
    {
        return $this->pictureWidth;
    }

    /**
     * @param int $pictureWidth
     */
    public function setPictureWidth(int $pictureWidth)
    {
        $this->pictureWidth = $pictureWidth;
    }

    /**
     * @return int|null
     */
    public function getPictureHeight(): ?int
    {
        return $this->pictureHeight;
    }

    /**
     * @param int $pictureHeight
     */
    public function setPictureHeight(int $pictureHeight)
    {
        $this->pictureHeight = $pictureHeight;
    }

    /**
     * @return string|null
     */
    public function getThumbnailUrl(): ?string
    {
        return $this->thumbnailUrl;
    }

    /**
     * @param string|null $thumbnailUrl
     */
    public function setThumbnailUrl(?string $thumbnailUrl)
    {
        $this->thumbnailUrl = $thumbnailUrl;
    }

    /**
     * @return Collection
     */
    public function getResponses(): Collection
    {
        return $this->responses;
    }

    /**
     * @param Response $response
     */
    public function addResponse(Response $response): void
    {
        $this->responses->add($response);
    }

    /**
     * @param Response $response
     */
    public function removeResponse(Response $response): void
    {
        $this->responses->removeElement($response);
    }

    /**
     * @return UploadedFile|null
     */
    public function getPictureFile(): ?UploadedFile
    {
        return $this->pictureFile;
    }

    /**
     * @param UploadedFile|null $pictureFile
     */
    public function setPictureFile(?UploadedFile $pictureFile)
    {
        $this->pictureFile = $pictureFile;
    }

    /**
     * @return UploadedFile|null
     */
    public function getThumbnailFile(): ?UploadedFile
    {
        return $this->thumbnailFile;
    }

    /**
     * @param UploadedFile|null $thumbnailFile
     */
    public function setThumbnailFile(?UploadedFile $thumbnailFile)
    {
        $this->thumbnailFile = $thumbnailFile;
    }
}
