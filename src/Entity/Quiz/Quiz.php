<?php

namespace App\Entity\Quiz;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Quiz
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
     * @var boolean
     *
     * @ORM\Column(name="displayResponse", type="boolean")
     */
    private $displayResponse;

    /**
     * @var boolean
     *
     * @ORM\Column(name="displayTrick", type="boolean")
     */
    private $displayTrick;

    /**
     * @var boolean
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
     * @ORM\Column(name="pictureUrl", type="text")
     */
    private $pictureUrl;

    /**
     * @var string
     *
     * @ORM\Column(name="thumbnailUrl", type="text")
     */
    private $thumbnailUrl;

    /**
     * @var Collection $responses
     *
     * @ORM\OneToMany(targetEntity="Response", mappedBy="quiz", cascade={"remove"})
     * @ORM\OrderBy({"title" = "ASC"})
     */
    private $responses;

    function __construct()
    {
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
    public function getThumbnailUrl(): string
    {
        return $this->thumbnailUrl;
    }

    /**
     * @param string $thumbnailUrl
     */
    public function setThumbnailUrl(string $thumbnailUrl)
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
}
