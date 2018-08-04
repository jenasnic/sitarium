<?php

namespace App\Entity\Quiz;

use Doctrine\ORM\Mapping as ORM;

/**
 * Response
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
     * @ORM\Column(name="positionX", type="integer")
     */
    private $positionX;

    /**
     * @var int
     *
     * @ORM\Column(name="positionY", type="integer")
     */
    private $positionY;

    /**
     * @var int
     *
     * @ORM\Column(name="size", type="integer")
     */
    private $size;

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

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
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
     * @return int
     */
    public function getPositionX(): int
    {
        return $this->positionX;
    }

    /**
     * @param int $positionX
     */
    public function setPositionX(int $positionX)
    {
        $this->positionX = $positionX;
    }

    /**
     * @return int
     */
    public function getPositionY(): int
    {
        return $this->positionY;
    }

    /**
     * @param int $positionY
     */
    public function setPositionY(int $positionY)
    {
        $this->positionY = $positionY;
    }

    /**
     * @return int
     */
    public function getSize(): int
    {
        return $this->size;
    }

    /**
     * @param int $size
     */
    public function setSize(int $size)
    {
        $this->size = $size;
    }

    /**
     * @return string
     */
    public function getPictureUrl(): string
    {
        return $this->pictureUrl;
    }

    /**
     * @param string $pictureUrl
     */
    public function setPictureUrl(string $pictureUrl)
    {
        $this->pictureUrl = $pictureUrl;
    }

    /**
     * @return string
     */
    public function getTagline(): string
    {
        return $this->tagline;
    }

    /**
     * @param string $tagline
     */
    public function setTagline(string $tagline)
    {
        $this->tagline = $tagline;
    }

    /**
     * @return string
     */
    public function getOverview(): string
    {
        return $this->overview;
    }

    /**
     * @param string $overview
     */
    public function setOverview(string $overview)
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
