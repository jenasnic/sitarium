<?php

namespace App\Entity\Quiz;

use Doctrine\ORM\Mapping as ORM;

/**
 * Response.
 *
 * @ORM\Table(name="quiz_response")
 * @ORM\Entity(repositoryClass="App\Repository\Quiz\ResponseRepository")
 */
class Response
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
     * @var int
     *
     * @ORM\Column(name="tmdbId", type="integer", nullable=true)
     */
    private $tmdbId;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="responses", type="text")
     */
    private $responses;

    /**
     * @var string
     *
     * @ORM\Column(name="trick", type="text")
     */
    private $trick;

    /**
     * @var int
     *
     * @ORM\Column(name="positionX", type="float", nullable=true)
     */
    private $positionX;

    /**
     * @var int
     *
     * @ORM\Column(name="positionY", type="float", nullable=true)
     */
    private $positionY;

    /**
     * @var int
     *
     * @ORM\Column(name="width", type="float", nullable=true)
     */
    private $width;

    /**
     * @var int
     *
     * @ORM\Column(name="height", type="float", nullable=true)
     */
    private $height;

    /**
     * @var string
     *
     * @ORM\Column(name="pictureUrl", type="string", length=255, nullable=true)
     */
    private $pictureUrl;

    /**
     * @var string
     *
     * @ORM\Column(name="tagline", type="text", nullable=true)
     */
    private $tagline;

    /**
     * @var string
     *
     * @ORM\Column(name="overview", type="text", nullable=true)
     */
    private $overview;

    /**
     * @var Quiz
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Quiz\Quiz", inversedBy="responses")
     * @ORM\JoinColumn(nullable=false)
     */
    private $quiz;

    public function __construct()
    {
        $this->title = '';
        $this->responses = '';
        $this->trick = '';
        $this->size = 1;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return int|null
     */
    public function getTmdbId(): ?int
    {
        return $this->tmdbId;
    }

    /**
     * @param int|null $tmdbId
     */
    public function setTmdbId(?int $tmdbId)
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
     * @return string
     */
    public function getResponses(): string
    {
        return $this->responses;
    }

    /**
     * @param string $responses
     */
    public function setResponses(string $responses)
    {
        $this->responses = $responses;
    }

    /**
     * @return string
     */
    public function getTrick(): string
    {
        return $this->trick;
    }

    /**
     * @param string $trick
     */
    public function setTrick(string $trick)
    {
        $this->trick = $trick;
    }

    /**
     * @return float|null
     */
    public function getPositionX(): ?float
    {
        return $this->positionX;
    }

    /**
     * @param float $positionX
     */
    public function setPositionX(float $positionX)
    {
        $this->positionX = $positionX;
    }

    /**
     * @return float|null
     */
    public function getPositionY(): ?float
    {
        return $this->positionY;
    }

    /**
     * @param float $positionY
     */
    public function setPositionY(float $positionY)
    {
        $this->positionY = $positionY;
    }

    /**
     * @return float|null
     */
    public function getWidth(): ?float
    {
        return $this->width;
    }

    /**
     * @param float $width
     */
    public function setWidth(float $width)
    {
        $this->width = $width;
    }

    /**
     * @return float|null
     */
    public function getHeight(): ?float
    {
        return $this->height;
    }

    /**
     * @param float $height
     */
    public function setHeight(float $height)
    {
        $this->height = $height;
    }

    /**
     * @return string|null
     */
    public function getPictureUrl(): ?string
    {
        return $this->pictureUrl;
    }

    /**
     * @param string $pictureUrl|null
     */
    public function setPictureUrl(string $pictureUrl)
    {
        $this->pictureUrl = $pictureUrl;
    }

    /**
     * @return string|null
     */
    public function getTagline(): ?string
    {
        return $this->tagline;
    }

    /**
     * @param string $tagline|null
     */
    public function setTagline(?string $tagline)
    {
        $this->tagline = $tagline;
    }

    /**
     * @return string|null
     */
    public function getOverview(): ?string
    {
        return $this->overview;
    }

    /**
     * @param string $overview|null
     */
    public function setOverview(?string $overview)
    {
        $this->overview = $overview;
    }

    /**
     * @return Quiz
     */
    public function getQuiz(): Quiz
    {
        return $this->quiz;
    }

    /**
     * @param Quiz $quiz
     */
    public function setQuiz(Quiz $quiz)
    {
        $this->quiz = $quiz;
    }
}
