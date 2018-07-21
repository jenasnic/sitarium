<?php

namespace App\Service\Action;

use App\Model\Maze\ActorGraphItem;

/**
 * This class allows to build a kind of maze between actors linked together with common movies.
 * NOTE : Use graph built using ActorGraphBuilder
 * @see ActorGraphBuilder
 */
class RandomActorPathAction
{
    /**
     * Allows to build an array with actors linked together with common filmography movies.
     * NOTE : If no path found, choose a smaller actor count or increase actors list in graph.
     *
     * @param array $actorGraph Map of ActorGraphItem with TMDB identifier as key and ActorGraphItem as value.
     * @param int $mazeSize Number of linked actors we want to find (at least 2).
     *
     * @throws \InvalidArgumentException Throw exception if specified size doesn't allow to build list of actors...
     *
     * @return array Array of actors linked with common movies (size of array is 'mazeSize').
     */
    public function getPath(array $actorGraph, int $mazeSize): array
    {
        if ($mazeSize < 2) {
            throw new \InvalidArgumentException('Actor count must be equal or greater than 2.');
        }

        if ($mazeSize > count($actorGraph)) {
            throw new \InvalidArgumentException('Actor count is too large to build a path. Choose a smaller actor count size or increase actors list.');
        }

        // Browse all ActorGraphItem randomly and try to find a path with specified size
        $tmdbIdList = array_keys($actorGraph);
        shuffle($tmdbIdList);

        foreach ($tmdbIdList as $tmdbId) {
            $graphItem = $actorGraph[$tmdbId];

            $path = [];
            $path = $this->findPathWithSize($actorGraph, $graphItem, $path, $mazeSize);

            // If we have found a path matching specified parameters => return path as array of actors
            if ($mazeSize === count($path)) {
                $result = [];
                foreach ($path as $graphItem) {
                    $result[] = $graphItem->getActor();
                }

                return $result;
            }
        }

        return null;
    }

    /**
     * Allows to build path with ActorGraphItem depending on specified parameter.
     * NOTE : Recursive method used to build path...
     *
     * @param array $actorGraph Map of ActorGraphItem with TMDB identifier as key and ActorGraphItem as value.
     * @param ActorGraphItem $graphItem ActorGraphItem to use to build path (as new path step).
     * @param array $currentPath Current path of ActorGraphItem (path we are building recursively).
     * @param int $pathSize Size of path (for previous argument $currentPath) we want to get (i.e. actor count).
     *
     * @return array Array of ActorGraphItem matching current built path.
     */
    protected function findPathWithSize(array $actorGraph, ActorGraphItem $graphItem, array $currentPath, int $pathSize): array
    {
        // If specified ActorGraphItem already used in current path => stop here
        if (in_array($graphItem, $currentPath)) {
            return $currentPath;
        }

        // In other case, add specified item to current path
        $currentPath[] = $graphItem;

        // If current path reached required size => stop here
        if ($pathSize === count($currentPath)) {
            return $currentPath;
        }

        // Else keep building current path with linked actors of specified maze item.
        // NOTE : Browse linked actors randomly
        $randomLinkedActors = $this->shuffleArray($graphItem->getLinkedActors());
        foreach ($randomLinkedActors as $linkedGraphItem) {
            $newPath = $this->findPathWithSize($actorGraph, $linkedGraphItem, $currentPath, $pathSize);
            if ($pathSize === count($newPath)) {
                return $newPath;
            }
        }

        return $currentPath;
    }

    /**
     * @param array $array
     *
     * @return array
     */
    private function shuffleArray(array $array): array
    {
        $newArray = [];
        foreach ($array as $item) {
            $newArray[] = $item;
        }

        shuffle($newArray);

        return $newArray;
    }
}
