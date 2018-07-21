<?php

namespace App\Service\Action;

use App\Model\Maze\ActorGraphItem;

/**
 * This class allows to build the longest path linking actors using common movies.
 * NOTE : Use graph built using ActorGraphBuilder
 * @see ActorGraphBuilder
 */
class MaxActorPathAction
{
    /**
     * Allows to build the largest array with specified actors linked together with common movies.
     *
     * @param array $actorGraph Map of ActorGraphItem with TMDB identifier as key and ActorGraphItem as value.
     *
     * @throws \Exception Throw exception if not enough actor in specified actor graph...
     *
     * @return array Array of actors linked with common movies.
     */
    public function getPath(array $actorGraph): array
    {
        if (count($actorGraph) < 2) {
            throw new \Exception('Not enough actor in specified graph. Set an actor graph with at least 2 actors.');
        }

        // Browse all ActorGraphItem randomly (for a same graph we can get different path...)
        $tmdbIdList = array_keys($actorGraph);
        shuffle($tmdbIdList);

        $longestPath = [];
        foreach ($tmdbIdList as $tmdbId) {
            $graphItem = $actorGraph[$tmdbId];
            $path = [];
            $path = $this->findLongestPath($actorGraph, $graphItem, $path);

            // If longer path found from current actor => keep it
            if (count($path) > count($longestPath)) {
                $longestPath = $path;
            }
        }

        // Convert result as actor list (as Actor)
        $result = [];
        foreach ($longestPath as $graphItem) {
            $result[] = $graphItem->getActor();
        }

        return $result;
    }

    /**
     * Allows to find longuest path between actors. It is used to link a maximum of actors among actor graph.
     * NOTE : Recursive method used to find path...
     *
     * @param array $actorGraph Map of ActorGraphItem with TMDB identifier as key and ActorGraphItem as value.
     * @param ActorGraphItem $currentActor ActorGraphItem to use as starting point to find longuest path.
     * @param array $currentPath Current path of ActorGraphItem (path we are building recursively).
     *
     * @return array Array of ActorGraphItem matching longest path found.
     */
    protected function findLongestPath(array $actorGraph, ActorGraphItem $currentActor, array $currentPath): array
    {
        // If current actor already used in current path => stop here
        if (in_array($currentActor, $currentPath)) {
            return $currentPath;
        }

        // Update current path
        $currentPath[] = $currentActor;
        $longestPath = null;

        // Browse each linked actor and keep building path for a longer one
        // NOTE : Linked actors list should never be empty
        foreach ($currentActor->getLinkedActors() as $linkedActor) {
            $newPath = $this->findLongestPath($actorGraph, $linkedActor, $currentPath);

            // If new path is longer than previous path => use it as longest path
            if (count($newPath) > count($longestPath)) {
                $longestPath = $newPath;
            }
        }

        // NOTE : This condition should never occurs (actor should always be linked to another actor...)
        if ($longestPath == null) {
            $longestPath = $currentPath;
        }

        return $longestPath;
    }
}
