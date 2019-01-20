<?php

namespace App\Entity\Quiz;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Quiz.
 *
 * @ORM\Table(name="quiz")
 * @ORM\Entity(repositoryClass="App\Repository\Quiz\QuizRepository")
 */
class Quiz
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var bool
     *
     * @ORM\Column(name="displayResponse", type="boolean")
     */
    private $displayResponse;

    /**
     * @var bool
     *
     * @ORM\Column(name="displayTrick", type="boolean")
     */
    private $displayTrick;

    /**
     * @var bool
     *
     * @ORM\Column(name="published", type="boolean")
     */
    private $published;

    /**
     * @var int
     *
     * @ORM\Column(name="rank", type="integer")
     */
    private $rank;

    /**
     * @var string
     *
     * @ORM\Column(name="pictureUrl", type="text", nullable=true)
     */
    private $pictureUrl;

    /**
     * @var int
     *
     * @ORM\Column(name="pictureWidth", type="integer", nullable=true)
     */
    private $pictureWidth;

    /**
     * @var int
     *
     * @ORM\Column(name="pictureHeight", type="integer", nullable=true)
     */
    private $pictureHeight;

    /**
     * @var string
     *
     * @ORM\Column(name="thumbnailUrl", type="text", nullable=true)
     */
    private $thumbnailUrl;

    /**
     * @var Collection
     *
     * @ORM\OneToMany(targetEntity="Response", mappedBy="quiz", cascade={"remove"})
     * @ORM\OrderBy({"title" = "ASC"})
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
     * @return int
     */
    public function getId(): int
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
    public function addResponse(Response $response)
    {
        $this->responses->add($response);
    }

    /**
     * @param Response $response
     */
    public function removeResponse(Response $response)
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
