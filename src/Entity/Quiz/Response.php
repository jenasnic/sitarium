<?php

namespace App\Entity\Quiz;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="quiz_response")
 * @ORM\Entity(repositoryClass="App\Repository\Quiz\ResponseRepository")
 */
class Response
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(name="id", type="integer")
     */
    private ?int $id = null;

    /**
     * @ORM\Column(name="tmdbId", type="integer", nullable=true)
     */
    private ?int $tmdbId = null;

    /**
     * @ORM\Column(name="title", type="string", length=255)
     */
    private ?string $title = null;

    /**
     * @ORM\Column(name="responses", type="text")
     */
    private ?string $responses = null;

    /**
     * @ORM\Column(name="trick", type="text")
     */
    private ?string $trick = null;

    /**
     * @ORM\Column(name="positionX", type="float", nullable=true)
     */
    private ?float $positionX = null;

    /**
     * @ORM\Column(name="positionY", type="float", nullable=true)
     */
    private ?float $positionY = null;

    /**
     * @ORM\Column(name="width", type="float", nullable=true)
     */
    private ?float $width = null;

    /**
     * @ORM\Column(name="height", type="float", nullable=true)
     */
    private ?float $height = null;

    /**
     * @ORM\Column(name="pictureUrl", type="string", length=255, nullable=true)
     */
    private ?string $pictureUrl = null;

    /**
     * @ORM\Column(name="tagline", type="text", nullable=true)
     */
    private ?string $tagline = null;

    /**
     * @ORM\Column(name="overview", type="text", nullable=true)
     */
    private ?string $overview = null;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Quiz\Quiz", inversedBy="responses")
     * @ORM\JoinColumn(nullable=false)
     */
    private ?Quiz $quiz = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTmdbId(): ?int
    {
        return $this->tmdbId;
    }

    public function setTmdbId(?int $tmdbId): void
    {
        $this->tmdbId = $tmdbId;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getResponses(): ?string
    {
        return $this->responses;
    }

    public function setResponses(string $responses): void
    {
        $this->responses = $responses;
    }

    public function getTrick(): ?string
    {
        return $this->trick;
    }

    public function setTrick(string $trick): void
    {
        $this->trick = $trick;
    }

    public function getPositionX(): ?float
    {
        return $this->positionX;
    }

    public function setPositionX(?float $positionX): void
    {
        $this->positionX = $positionX;
    }

    public function getPositionY(): ?float
    {
        return $this->positionY;
    }

    public function setPositionY(?float $positionY): void
    {
        $this->positionY = $positionY;
    }

    public function getWidth(): ?float
    {
        return $this->width;
    }

    public function setWidth(?float $width): void
    {
        $this->width = $width;
    }

    public function getHeight(): ?float
    {
        return $this->height;
    }

    public function setHeight(?float $height): void
    {
        $this->height = $height;
    }

    public function getPictureUrl(): ?string
    {
        return $this->pictureUrl;
    }

    public function setPictureUrl(?string $pictureUrl)
    {
        $this->pictureUrl = $pictureUrl;
    }

    public function getTagline(): ?string
    {
        return $this->tagline;
    }

    public function setTagline(?string $tagline): void
    {
        $this->tagline = $tagline;
    }

    public function getOverview(): ?string
    {
        return $this->overview;
    }

    public function setOverview(?string $overview): void
    {
        $this->overview = $overview;
    }

    public function getQuiz(): Quiz
    {
        return $this->quiz;
    }

    public function setQuiz(Quiz $quiz): void
    {
        $this->quiz = $quiz;
    }
}
