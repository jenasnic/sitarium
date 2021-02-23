<?php

namespace App\Model\Tmdb;

use DateTime;

class Movie
{
    private int $id;

    private ?string $title = null;

    private ?DateTime $releaseDate = null;

    private ?string $posterPath = null;

    private ?string $tagline = null;

    private ?string $overview = null;

    private ?int $voteCount = null;

    private ?float $voteAverage = null;

    private ?float $popularity = null;

    private ?string $character = null;

    /**
     * @var Genre[]|array<Genre>
     */
    private array $genres = [];

    /**
     * @var array<int>
     */
    private array $genreIds = [];

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getTitle(): ?string
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

    public function getPosterPath(): ?string
    {
        return $this->posterPath;
    }

    public function setPosterPath(?string $posterPath): void
    {
        $this->posterPath = $posterPath;
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

    public function getVoteCount(): ?int
    {
        return $this->voteCount;
    }

    public function setVoteCount(int $voteCount): void
    {
        $this->voteCount = $voteCount;
    }

    public function getVoteAverage(): ?float
    {
        return $this->voteAverage;
    }

    public function setVoteAverage(float $voteAverage): void
    {
        $this->voteAverage = $voteAverage;
    }

    public function getPopularity(): ?float
    {
        return $this->popularity;
    }

    public function setPopularity(float $popularity): void
    {
        $this->popularity = $popularity;
    }

    public function getCharacter(): ?string
    {
        return $this->character;
    }

    public function setCharacter(?string $character): void
    {
        $this->character = $character;
    }

    /**
     * @return Genre[]|array<Genre>
     */
    public function getGenres(): array
    {
        return $this->genres;
    }

    /**
     * @param Genre[]|array<Genre> $genres
     */
    public function setGenres(array $genres): void
    {
        $this->genres = $genres;
    }

    /**
     * @return array<int>
     */
    public function getGenreIds(): array
    {
        return $this->genreIds;
    }

    /**
     * @param array<int> $genreIds
     */
    public function setGenreIds(array $genreIds): void
    {
        $this->genreIds = $genreIds;
    }
}
