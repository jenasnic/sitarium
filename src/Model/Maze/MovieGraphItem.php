<?php

namespace App\Model\Maze;

use App\Entity\Maze\Movie;

class MovieGraphItem
{
    /**
     * @var Movie
     */
    private $movie;

    /**
     * @var array
     */
    private $linkedMovies;

    /**
     * @var int
     */
    private $bestPathPosition = 0;

    /**
     * @var int
     */
    private $bestPathSize = 0;

    /**
     * @param Movie $movie
     */
    public function __construct(Movie $movie)
    {
        $this->movie = $movie;
        $this->linkedMovies = [];
    }

    /**
     * @return Movie
     */
    public function getMovie(): Movie
    {
        return $this->movie;
    }

    /**
     * @param Movie $movie
     */
    public function setMovie(Movie $movie)
    {
        $this->movie = $movie;
    }

    /**
     * @return array
     */
    public function getLinkedMovies(): array
    {
        return $this->linkedMovies;
    }

    /**
     * @param MovieGraphItem $linkedMovie
     *
     * @return MovieGraphItem
     */
    public function addLinkedActor(MovieGraphItem $linkedMovie): self
    {
        $this->linkedMovies[] = $linkedMovie;

        return $this;
    }

    /**
     * @return int
     */
    public function getBestPathPosition(): int
    {
        return $this->bestPathPosition;
    }

    /**
     * @param int $bestPathPosition
     */
    public function setBestPathPosition(int $bestPathPosition)
    {
        $this->bestPathPosition = $bestPathPosition;
    }

    /**
     * @return int
     */
    public function getBestPathSize(): int
    {
        return $this->bestPathSize;
    }

    /**
     * @param int $bestPathSize
     */
    public function setBestPathSize($bestPathSize)
    {
        $this->bestPathSize = $bestPathSize;
    }
}
