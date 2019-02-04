<?php

namespace App\Model\Maze;

class MazeItem
{
    /**
     * @var int
     */
    private $tmdbId;

    /**
     * @var string
     */
    private $displayName;

    /**
     * @var string
     */
    private $pictureUrl;

    /**
     * @param int $tmdbId
     * @param string $displayName
     * @param string $pictureUrl
     */
    public function __construct(int $tmdbId, string $displayName, string $pictureUrl)
    {
        $this->tmdbId = $tmdbId;
        $this->displayName = $displayName;
        $this->pictureUrl = $pictureUrl;
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
     *
     * @return MazeItem
     */
    public function setTmdbId(int $tmdbId): self
    {
        $this->tmdbId = $tmdbId;

        return $this;
    }

    /**
     * @return string
     */
    public function getDisplayName(): string
    {
        return $this->displayName;
    }

    /**
     * @param string $displayName
     *
     * @return MazeItem
     */
    public function setDisplayName(string $displayName): self
    {
        $this->displayName = $displayName;

        return $this;
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
     *
     * @return MazeItem
     */
    public function setPictureUrl(string $pictureUrl): self
    {
        $this->pictureUrl = $pictureUrl;

        return $this;
    }
}
