<?php

namespace App\Service\Action;

use App\Entity\Maze\Actor;
use App\Model\Maze\ActorGraphItem;

/**
 * This class allows to build the shortest path between actors using common movies.
 * NOTE : Use graph built using ActorGraphBuilder
 * @see ActorGraphBuilder
 */
class MinActorPathAction
{
    /**
     * Allows to find shortest path between two specified actors using common movies between actors.
     *
     * @param array $actorGraph Map of ActorGraphItem with TMDB identifier as key and ActorGraphItem as value.
     * @param Actor $actor1 First actor we want to find the shortest path to second actor.
     * @param Actor $actor2 Second actor we want to find the shortest path from first actor.
     */
    public function getPath(array $actorGraph, Actor $actor1, Actor $actor2)
    {
        $currentPath = [];
        $currentActor = $actorGraph[$actor1->getTmdbId()];
        $actorToReach = $actorGraph[$actor2->getTmdbId()];

        $shortestPath = $this->findShortestPath($actorGraph, $currentActor, $actorToReach, $currentPath);

        // If we have found a path between actors => return it as array of actors
        if ($shortestPath) {
            $result = [];
            foreach ($shortestPath as $graphItem) {
                $result[] = $graphItem->getActor();
            }

            return $result;
        } else {
            return null;
        }
    }

    /**
     * Allows to find shortest path between actors.
     * NOTE : Recursive method used to find path...
     *
     * @param array $actorGraph Map of ActorGraphItem with TMDB identifier as key and ActorGraphItem as value.
     * @param ActorGraphItem $currentActor ActorGraphItem to use as starting point to find shortest path.
     * @param ActorGraphItem $actorToReach ActorGraphItem to use as ending point to find shortest path.
     * @param array $currentPath Current path of MovieGraphItem (path we are building recursively).
     *
     * @return array Array of ActorGraphItem matching shortest path or NULL if no path found.
     */
    protected function findShortestPath(array $actorGraph, ActorGraphItem $currentActor, ActorGraphItem $actorToReach, array $currentPath): array
    {
        // If path already found => current path must be better
        if ($currentActor->getBestPathSize() > 0 && count($currentPath) >= $currentActor->getBestPathSize()) {
            return null;
        }

        // If actor reached => stop here (path OK)
        if ($currentActor == $actorToReach) {
            $currentPath[] = $currentActor;
            return $currentPath;
        }

        // If current actor already used in current path => stop here (wrong path)
        if (in_array($currentActor, $currentPath)) {
            return null;
        }

        // If current actor already used in a matching path and current path already longer => stop here (path will be too long)
        if ($currentActor->getBestPathPosition() > 0 && count($currentPath) >= $currentActor->getBestPathPosition()) {
            return null;
        }

        // If path already found and current actor already used in a matching path with longer path => stop here (path will be too long)
        if ($currentActor->getBestPathSize() > 0
            && $currentActor->getBestPathPosition() > 0
            && $currentActor->getBestPathPosition() >= $currentActor->getBestPathSize()
        ) {
            return null;
        }

        // If path still OK => add curent actor and process linked actors...
        $currentPath[] = $currentActor;

        // If actor to reach exist in linked actors => use it
        if (in_array($actorToReach, $currentActor->getLinkedActors())) {
            $currentPath[] = $actorToReach;
            return $currentPath;
        }

        $minPath = null;
        foreach ($currentActor->getLinkedActors() as $linkedActor) {
            $newPath = $this->findShortestPath($actorGraph, $linkedActor, $actorToReach, $currentPath);

            // If path found => check if OK
            if ($newPath !== null) {
                // If no best path found yet or better path found
                if ($currentActor->getBestPathSize() === 0 || count($newPath) <= $currentActor->getBestPathSize()) {
                    $this->setBestPathSizeForGraphItem($actorGraph, count($newPath));
                    $minPath = $newPath;
                    $this->updateBestPathPositionForGraphItem($newPath);
                }
            }
        }

        return $minPath;
    }

    /**
     * @param array $actorGraph
     * @param int $bestPathSize
     */
    private function setBestPathSizeForGraphItem(array $actorGraph, int $bestPathSize)
    {
        /** @var ActorGraphItem $actorGraphItem */
        foreach ($actorGraph as $actorGraphItem) {
            $actorGraphItem->setBestPathSize($bestPathSize);
        }
    }

    /**
     * @param array $actorPath
     */
    private function updateBestPathPositionForGraphItem(array $actorPath)
    {
        for ($i = 0; $i < count($actorPath); $i++) {
            if (0 === $actorPath[$i]->getBestPathPosition() || $actorPath[$i]->getBestPathPosition() > $i) {
                $actorPath[$i]->setBestPathPosition($i);
            }
        }
    }
}
