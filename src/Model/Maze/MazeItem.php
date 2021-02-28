<?php

namespace App\Model\Maze;

class MazeItem
{
    private int $tmdbId;

    private string $displayName;

    private string $pictureUrl;

    public function __construct(int $tmdbId, string $displayName, string $pictureUrl)
    {
        $this->tmdbId = $tmdbId;
        $this->displayName = $displayName;
        $this->pictureUrl = $pictureUrl;
    }

    public function getTmdbId(): int
    {
        return $this->tmdbId;
    }

    public function setTmdbId(int $tmdbId): self
    {
        $this->tmdbId = $tmdbId;

        return $this;
    }

    public function getDisplayName(): string
    {
        return $this->displayName;
    }

    public function setDisplayName(string $displayName): self
    {
        $this->displayName = $displayName;

        return $this;
    }

    public function getPictureUrl(): string
    {
        return $this->pictureUrl;
    }

    public function setPictureUrl(string $pictureUrl): self
    {
        $this->pictureUrl = $pictureUrl;

        return $this;
    }
}
