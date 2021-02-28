<?php

namespace App\Service\Maze;

use App\Entity\Maze\Actor;
use App\Entity\Maze\FilmographyMovie;
use Doctrine\Common\Collections\Collection;

/**
 * This class allows to build helper to resolve actor path.
 */
class ActorPathHelpFactory
{
    /**
     * @param Actor[]|array<Actor> $actorPath
     * @param int $minVoteCount minimum vote count value used to extract movies from specified actors (difficulty level)
     * @param int $level difficulty level (0 easy, 1 medium, 2 difficult) used to reduce help list count
     *
     * @return FilmographyMovie[]|array<FilmographyMovie>
     */
    public function getMovies(array $actorPath, int $minVoteCount, int $level): array
    {
        $movieList = [];
        $movieCountPerActor = 2 * ($level + 1);
        $restCountForMovie = 0;

        $actorCount = count($actorPath);

        // Browse each actor and get filmography to fill helper movies list
        // NOTE : we try to get same movie count for each actor using actorCountPerMovie value (depending on difficulty level)
        for ($i = 0; $i < $actorCount - 1; ++$i) {
            $currentActor = $actorPath[$i];
            $nextActor = $actorPath[$i + 1];

            // Get common movies and mix them
            $commonMovies = $this->getCommonMovieList($currentActor->getMovies(), $nextActor->getMovies(), $minVoteCount);
            shuffle($commonMovies);

            $addedMovieCount = 0 - $restCountForMovie;

            // Fill movie list with common movies and try to fill list until requested size is reached (i.e. value of movieCountPerActor)
            $addedMovieCount = $this->fillMovieListWithConstraints($movieList, $commonMovies, $addedMovieCount, $movieCountPerActor, $minVoteCount);

            // If not enough movies added => keep filling movie list with movies from current actor
            if ($addedMovieCount < $movieCountPerActor) {
                $currentMovieActorList = $currentActor->getMovies()->toArray();
                $addedMovieCount = $this->fillMovieListWithConstraints($movieList, $currentMovieActorList, $addedMovieCount, $movieCountPerActor, $minVoteCount);
            }

            // If still not enough movies added, update rest value to get further more movies on next actor...
            if ($addedMovieCount < $movieCountPerActor) {
                $restCountForMovie = $movieCountPerActor - $addedMovieCount;
            }

            // If second to last movie reached and rest movie count => try to get movies from last actor (i.e. nextActor value)
            if ($i === $actorCount - 2 && $restCountForMovie > 0) {
                $nextMovieActorList = $nextActor->getMovies()->toArray();
                $addedMovieCount = $this->fillMovieListWithConstraints($movieList, $nextMovieActorList, $addedMovieCount, $movieCountPerActor, $minVoteCount);
            }
        }

        // Sort list alphabetically and return it
        usort($movieList, function (FilmographyMovie $movie1, FilmographyMovie $movie2) {
            return strcmp($movie1->getTitle(), $movie2->getTitle());
        });

        return $movieList;
    }

    /**
     * Allows to fill movie list using specified parameters.
     * NOTE : Specified movie list to fill will be updated in this method.
     *
     * @param FilmographyMovie[]|array<FilmographyMovie> $movieListToFill array of movies we want to fill until reaching movie count per actor value
     * @param FilmographyMovie[]|array<FilmographyMovie> $movieListSource array of movies used to fill previous parameter
     * @param int $addedMovieCount current movie count added to movie list we are filling
     * @param int $movieCountPerActor number of movies we want to add to movie list we are filling
     * @param int $minVoteCount minimum vote count value for movies used to build movie list (difficulty level)
     *
     * @return int updated value for added movie count
     */
    private function fillMovieListWithConstraints(
        array &$movieListToFill,
        array $movieListSource,
        int $addedMovieCount,
        int $movieCountPerActor,
        int $minVoteCount
    ): int {
        for ($j = 0; $j < count($movieListSource) && $addedMovieCount < $movieCountPerActor; ++$j) {
            $movieToAdd = $movieListSource[$j];
            if (!in_array($movieToAdd, $movieListToFill) && $movieToAdd->getVoteCount() >= $minVoteCount) {
                $movieListToFill[] = $movieToAdd;
                ++$addedMovieCount;
            }
        }

        return $addedMovieCount;
    }

    /**
     * Allows to get common movies between 2 specified movie list.
     *
     * @param FilmographyMovie[]|Collection<int, FilmographyMovie> $movieList1
     * @param FilmographyMovie[]|Collection<int, FilmographyMovie> $movieList2
     * @param int $minVoteCount minimum vote count value for common movies to keep (difficulty level)
     *
     * @return FilmographyMovie[]|array<FilmographyMovie>
     */
    private function getCommonMovieList(Collection $movieList1, Collection $movieList2, int $minVoteCount): array
    {
        $resultList = [];

        // Browse movies from first list
        foreach ($movieList1 as $movie1) {
            if ($movie1->getVoteCount() >= $minVoteCount) {
                // If movie exist in second list => add it to result list
                foreach ($movieList2 as $movie2) {
                    if ($movie1->getTmdbId() === $movie2->getTmdbId()) {
                        $resultList[] = $movie1;
                    }
                }
            }
        }

        return $resultList;
    }
}
