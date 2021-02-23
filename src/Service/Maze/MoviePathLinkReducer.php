<?php

namespace App\Service\Maze;

use App\Entity\Maze\CastingActor;
use App\Entity\Maze\Movie;

/**
 * This class allows to keep only one common actor between each movie of specified path.
 * NOTE : Keep first actor per default.
 */
class MoviePathLinkReducer
{
    /**
     * @param Movie[]|array<Movie> $moviePath
     */
    public function reduceLinks(array $moviePath): void
    {
        // Process movies to keep only first common actor that allow a link with next movie
        for ($i = 0; $i < count($moviePath); ++$i) {
            $movie = $moviePath[$i];

            // If we have a following actor => keep only first common actor
            if ($i < count($moviePath) - 1) {
                $followingMovie = $moviePath[$i + 1];
                $firstCommonActor = $this->extractFirstCommonActorForMovies($movie, $followingMovie);
                $movie->getActors()->clear();
                $movie->addActor($firstCommonActor);
            } else {
                $movie->getActors()->clear();
            }
        }
    }

    protected function extractFirstCommonActorForMovies(Movie $movie1, Movie $movie2): CastingActor
    {
        foreach ($movie1->getActors() as $actor) {
            if ($movie2->getActors()->contains($actor)) {
                return $actor;
            }
        }

        return null;
    }
}
