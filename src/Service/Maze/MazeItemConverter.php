<?php

namespace App\Service\Maze;

use App\Entity\Maze\Actor;
use App\Entity\Maze\Movie;
use App\Entity\Maze\CastingActor;
use App\Entity\Maze\FilmographyMovie;
use App\Model\Maze\MazeItem;
use App\Tool\TmdbUtil;

/**
 * This class allows to convert Actor or Movie as MazeItem keeping common properties with single acces name.
 */
class MazeItemConverter
{
    /**
     * @param Actor $actor
     *
     * @return MazeItem
     */
    public function convertActor(Actor $actor): MazeItem
    {
        return new MazeItem(
            $actor->getTmdbId(),
            $actor->getFullname(),
            TmdbUtil::getBasePictureUrl().$actor->getPictureUrl()
        );
    }

    /**
     * @param Actor[]|array $actors
     *
     * @return MazeItem[]|array
     */
    public function convertActors(array $actors): array
    {
        $result = [];

        foreach ($actors as $actor) {
            $result[] = $this->convertActor($actor);
        }

        return $result;
    }

    /**
     * @param CastingActor $actor
     *
     * @return MazeItem
     */
    public function convertCastingActor(CastingActor $actor): MazeItem
    {
        return new MazeItem(
            $actor->getTmdbId(),
            $actor->getFullname(),
            TmdbUtil::getBasePictureUrl().$actor->getPictureUrl()
        );
    }

    /**
     * @param CastingActor[]|array $actors
     *
     * @return MazeItem[]|array
     */
    public function convertCastingActors(array $actors): array
    {
        $result = [];

        foreach ($actors as $actor) {
            $result[] = $this->convertCastingActor($actor);
        }

        return $result;
    }

    /**
     * @param Movie $movie
     *
     * @return MazeItem
     */
    public function convertMovie(Movie $movie): MazeItem
    {
        return new MazeItem(
            $movie->getTmdbId(),
            $movie->getTitle(),
            TmdbUtil::getBasePictureUrl().$movie->getPictureUrl()
        );
    }

    /**
     * @param Movie[]|array $movies
     *
     * @return MazeItem[]|array
     */
    public function convertMovies(array $movies): array
    {
        $result = [];

        foreach ($movies as $movie) {
            $result[] = $this->convertMovie($movie);
        }

        return $result;
    }

    /**
     * @param FilmographyMovie $movie
     *
     * @return MazeItem
     */
    public function convertFilmographyMovie(FilmographyMovie $movie): MazeItem
    {
        return new MazeItem(
            $movie->getTmdbId(),
            $movie->getTitle(),
            TmdbUtil::getBasePictureUrl().$movie->getPictureUrl()
        );
    }

    /**
     * @param FilmographyMovie[]|array $movies
     *
     * @return MazeItem[]|array
     */
    public function convertFilmographyMovies(array $movies): array
    {
        $result = [];

        foreach ($movies as $movie) {
            $result[] = $this->convertFilmographyMovie($movie);
        }

        return $result;
    }
}
