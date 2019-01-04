<?php

namespace App\Service\Maze;

use App\Model\Maze\MazeGraphItem;

/**
 * This class allows to build a kind of maze between items of specified graph.
 * NOTE : Use graph built using ActorGraphBuilder or MovieGraphBuilder.
 *
 * @see ActorGraphBuilder
 * @see MovieGraphBuilder
 */
class RandomPathFinder
{
    /**
     * Allows to build an array with linked items from specfied graph with specified size.
     * NOTE : If no path found, choose a smaller items count or increase items list in graph.
     *
     * @param array $mazeGraph map of MazeGraphItem with TMDB identifier as key and MazeGraphItem as value
     * @param int $mazeSize number of linked items we want to find (at least 2)
     *
     * @throws \InvalidArgumentException Throw exception if specified size doesn't allows to build path of items...
     *
     * @return array|null array of items linked together (size of array is 'mazeSize')
     */
    public function find(array $mazeGraph, int $mazeSize): ?array
    {
        if ($mazeSize < 2) {
            throw new \InvalidArgumentException('Items count must be equal or greater than 2.');
        }

        if ($mazeSize > count($mazeGraph)) {
            throw new \InvalidArgumentException('Items count is too large to build a path. Choose a smaller items count size or increase items list in graph.');
        }

        // Browse all MazeGraphItem randomly and try to find a path with specified size
        $tmdbIdList = array_keys($mazeGraph);
        shuffle($tmdbIdList);

        foreach ($tmdbIdList as $tmdbId) {
            $graphItem = $mazeGraph[$tmdbId];

            $path = [];
            $path = $this->findPathWithSize($mazeGraph, $graphItem, $path, $mazeSize);

            // If we have found a path matching specified parameters => Convert result as item list (as Actor or Movie)
            if ($mazeSize === count($path)) {
                $result = [];
                foreach ($path as $graphItem) {
                    $result[] = $graphItem->getItem();
                }

                return $result;
            }
        }

        return null;
    }

    /**
     * Allows to build path with MazeGraphItem depending on specified parameters.
     * NOTE : Recursive method used to build path...
     *
     * @param array $mazeGraph map of MazeGraphItem with TMDB identifier as key and MazeGraphItem as value
     * @param MazeGraphItem $graphItem mazeGraphItem to use to build path (as new path step)
     * @param array $currentPath current path of MazeGraphItem (path we are building recursively)
     * @param int $pathSize Size of path (for previous argument $currentPath) we want to get (i.e. item count).
     *
     * @return array array of MazeGraphItem matching current built path
     */
    protected function findPathWithSize(array $mazeGraph, MazeGraphItem $graphItem, array $currentPath, int $pathSize): array
    {
        // If specified MazeGraphItem already used in current path => stop here
        if (in_array($graphItem, $currentPath)) {
            return $currentPath;
        }

        // In other case, add specified item to current path
        $currentPath[] = $graphItem;

        // If current path reached required size => stop here
        if ($pathSize === count($currentPath)) {
            return $currentPath;
        }

        // Else keep building current path with linked items of specified maze item.
        // NOTE : Browse linked items randomly
        $randomLinkedItems = $this->shuffleArray($graphItem->getLinkedItems());
        foreach ($randomLinkedItems as $linkedGraphItem) {
            $newPath = $this->findPathWithSize($mazeGraph, $linkedGraphItem, $currentPath, $pathSize);
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
