<?php

namespace App\Service;

use App\Entity\Maze\Actor;
use App\Entity\Maze\FilmographyMovie;

/**
 * This class allows to keep only one common movie between each actor of specified path.
 * NOTE : Keep only top rated common movie between actors.
 */
class ActorPathLinkReducer
{
    /**
     * @param array $actorPath
     */
    public function reduceLinks(array $actorPath)
    {
        // Process actors to keep only top rated movie that allow a link with next actor
        for ($i = 0; $i < count($actorPath); $i++) {
            $actor = $actorPath[$i];

            // If we have a following actor => keep only top rated common movie
            if ($i < count($actorPath) - 1) {
                $followingActor = $actorPath[$i + 1];
                $bestCommonMovie = $this->extractTopRatedCommonMovieForActors($actor, $followingActor);
                $actor->getMovies()->clear();
                $actor->addMovie($bestCommonMovie);
            } else {
                $actor->getMovies()->clear();
            }
        }
    }

    /**
     * Allows to get most known common movie between two specified actors.
     *
     * @param Actor $actor1 First actor we want to extract common movie with second actor.
     * @param Actor $actor2 Second actor we want to extract common movie with first actor.
     *
     * @return FilmographyMovie Common movie with best vote count.
     */
    protected function extractTopRatedCommonMovieForActors(Actor $actor1, Actor $actor2): FilmographyMovie
    {
        $topRatedMovie = null;

        foreach ($actor1->getMovies() as $movie) {
            if ($actor2->getMovies()->contains($movie)) {
                if ($topRatedMovie == null || $movie->getVoteCount() > $topRatedMovie->getVoteCount()) {
                    $topRatedMovie = $movie;
                }
            }
        }

        return $topRatedMovie;
    }
}
