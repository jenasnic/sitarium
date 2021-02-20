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
     * @var array
     */
    private $genres;

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
     * @return array
     */
    public function getGenres(): array
    {
        return $this->genres;
    }

    /**
     * @param array $genres
     */
    public function addGenre(Genre $genre)
    {
        $this->genres[] = $genre;
    }

}
