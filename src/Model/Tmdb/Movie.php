<?php

namespace App\Model\Tmdb;

class Movie
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $title;

    /**
     * @var \DateTime
     */
    private $releaseDate;

    /**
     * @var string
     */
    private $posterPath;

    /**
     * @var string
     */
    private $tagline;

    /**
     * @var string
     */
    private $overview;

    /**
     * @var int
     */
    private $voteCount;

    /**
     * @var float
     */
    private $voteAverage;

    /**
     * @var float
     */
    private $popularity;

    /**
     * @var string
     */
    private $character;

    /**
     * @var Genre[]|array<Genre>
     */
    private $genres;

    /**
     * @var array
     */
    private $genreIds;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id)
    {
        $this->id = $id;
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
     * @return \DateTime|null
     */
    public function getReleaseDate(): ?\DateTime
    {
        return $this->releaseDate;
    }

    /**
     * @param \DateTime|null $releaseDate
     */
    public function setReleaseDate(?\DateTime $releaseDate)
    {
        $this->releaseDate = $releaseDate;
    }

    /**
     * @return string|null
     */
    public function getPosterPath(): ?string
    {
        return $this->posterPath;
    }

    /**
     * @param string|null $posterPath
     */
    public function setPosterPath(?string $posterPath)
    {
        $this->posterPath = $posterPath;
    }

    /**
     * @return string|null
     */
    public function getTagline(): ?string
    {
        return $this->tagline;
    }

    /**
     * @param string|null $tagline
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
     * @param string|null $overview
     */
    public function setOverview(?string $overview)
    {
        $this->overview = $overview;
    }

    /**
     * @return number
     */
    public function getVoteCount()
    {
        return $this->voteCount;
    }

    /**
     * @param number $voteCount
     */
    public function setVoteCount($voteCount)
    {
        $this->voteCount = $voteCount;
    }

    /**
     * @return float
     */
    public function getVoteAverage()
    {
        return $this->voteAverage;
    }

    /**
     * @param float $voteAverage
     */
    public function setVoteAverage($voteAverage)
    {
        $this->voteAverage = $voteAverage;
    }

    /**
     * @return float
     */
    public function getPopularity()
    {
        return $this->popularity;
    }

    /**
     * @param float $popularity
     */
    public function setPopularity($popularity)
    {
        $this->popularity = $popularity;
    }

    /**
     * @return string
     */
    public function getCharacter()
    {
        return $this->character;
    }

    /**
     * @param string $character
     */
    public function setCharacter($character)
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
    public function setGenres(array $genres)
    {
        $this->genres = $genres;
    }

    /**
     * @return array
     */
    public function getGenreIds(): array
    {
        return $this->genreIds;
    }

    /**
     * @param array $genreIds
     */
    public function setGenreIds(array $genreIds)
    {
        $this->genreIds = $genreIds;
    }
}
