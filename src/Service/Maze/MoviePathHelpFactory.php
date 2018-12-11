<?php

namespace App\Service\Maze;

use Doctrine\Common\Collections\Collection;

/**
 * This class allows to build helper to resolve movie path.
 */
class MoviePathHelpFactory
{
    /**
     * Allows to get list of actors to use to resolve specified movie path (used to display help for movie maze...).
     *
     * @param array $moviePath array of movies we want to get casting as helper
     * @param int $level difficulty level (0 easy, 1 medium, 2 difficult) used to reduce help list count
     *
     * @return array array of actors matching specified parameters
     */
    public function getShuffledActors(array $moviePath, int $level): array
    {
        $actorList = [];
        $actorCountPerMovie = 2 * ($level + 1);
        $restCountForActor = 0;

        $movieCount = count($moviePath);

        // Browse each movie and get casting to fill helper actors list
        // NOTE : we try to get same actor count for each movie using actorCountPerMovie value (depending on difficulty level)
        for ($i = 0; $i < $movieCount - 1; ++$i) {
            $currentMovie = $moviePath[$i];
            $nextMovie = $moviePath[$i + 1];

            // Get common actors and mix them
            $commonActors = $this->getCommonActorList($currentMovie->getActors(), $nextMovie->getActors());
            shuffle($commonActors);

            $addedActorCount = 0 - $restCountForActor;

            // Fill actor list with common actors and try to fill list until requested size is reached (i.e. value of actorCountPerMovie)
            $addedActorCount = $this->fillActorListWithConstraints($actorList, $commonActors, $addedActorCount, $actorCountPerMovie);

            // If not enough actors added => keep filling actor list with actors from current movie
            if ($addedActorCount < $actorCountPerMovie) {
                $currentMovieActorList = $currentMovie->getActors()->toArray();
                $addedActorCount = $this->fillActorListWithConstraints($actorList, $currentMovieActorList, $addedActorCount, $actorCountPerMovie);
            }

            // If still not enough actors added, update rest value to get further more actors on next movie...
            if ($addedActorCount < $actorCountPerMovie) {
                $restCountForActor = $actorCountPerMovie - $addedActorCount;
            }

            // If second to last movie reached and rest actor count => try to get actors from last movie (i.e. nextMovie value)
            if ($i === $movieCount - 2 && $restCountForActor > 0) {
                $nextMovieActorList = $nextMovie->getActors()->toArray();
                $addedActorCount = $this->fillActorListWithConstraints($actorList, $nextMovieActorList, $addedActorCount, $actorCountPerMovie);
            }
        }

        // Mix result and return it
        shuffle($actorList);

        return $actorList;
    }

    /**
     * Allows to fill actor list using specified paramaters.
     * NOTE : Specified actor list to fill will be updated in this method.
     *
     * @param array $actorListToFill array of actors we want to fill until reaching actor count per movie value
     * @param array $actorListSource array of actors used to fill previous parameter
     * @param int $addedActorCount current actor count added to actor list we are filling
     * @param int $actorCountPerMovie number of actors we want to add to actor list we are filling
     *
     * @return int updated value for added actor count
     */
    private function fillActorListWithConstraints(
        array &$actorListToFill,
        array $actorListSource,
        int $addedActorCount,
        int $actorCountPerMovie
    ): int {
        for ($j = 0; $j < count($actorListSource) && $addedActorCount < $actorCountPerMovie; ++$j) {
            $actorToAdd = $actorListSource[$j];
            if (!in_array($actorToAdd, $actorListToFill)) {
                $actorListToFill[] = $actorToAdd;
                ++$addedActorCount;
            }
        }

        return $addedActorCount;
    }

    /**
     * Allows to get common actors between 2 specified actors list.
     *
     * @param Collection $actorList1
     * @param Collection $actorList2
     *
     * @return array
     */
    private function getCommonActorList(Collection $actorList1, Collection $actorList2): array
    {
        $resultList = [];

        // Browse actors from first list
        foreach ($actorList1 as $actor1) {
            // If actor exist in second list => add it to result list
            foreach ($actorList2 as $actor2) {
                if ($actor1->getTmdbId() === $actor2->getTmdbId()) {
                    $resultList[] = $actor1;
                }
            }
        }

        return $resultList;
    }
}
