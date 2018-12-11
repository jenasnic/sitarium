<?php

namespace App\Service\Maze;

use App\Model\Maze\MazeGraphItem;

/**
 * This class allows to build the longest path found for specified graph.
 * NOTE : Use graph built using ActorGraphBuilder or MovieGraphBuilder.
 *
 * @see ActorGraphBuilder
 * @see MovieGraphBuilder
 */
class MaxPathFinder
{
    /**
     * Allows to build the largest path for specified graph (among MazeGraphItem objects).
     *
     * @param array $mazeGraph map of MazeGraphItem with TMDB identifier as key and MazeGraphItem as value
     *
     * @throws \Exception Throw exception if not enough items in specified graph...
     *
     * @return array array of items linked together
     */
    public function find(array $mazeGraph): array
    {
        if (count($mazeGraph) < 2) {
            throw new \Exception('Not enough item in specified graph. Set a graph with at least 2 items.');
        }

        // Browse all MazeGraphItem randomly (for a same graph we can get different path...)
        $tmdbIdList = array_keys($mazeGraph);
        shuffle($tmdbIdList);

        $longestPath = [];
        foreach ($tmdbIdList as $tmdbId) {
            $graphItem = $mazeGraph[$tmdbId];
            $path = [];
            $path = $this->findLongestPath($mazeGraph, $graphItem, $path);

            // If longer path found from current item => keep it
            if (count($path) > count($longestPath)) {
                $longestPath = $path;
            }
        }

        // Convert result as item list (as Actor or Movie)
        $result = [];
        /** @var MazeGraphItem $graphItem */
        foreach ($longestPath as $graphItem) {
            $result[] = $graphItem->getItem();
        }

        return $result;
    }

    /**
     * Allows to find longuest path between items. It is used to link a maximum of items among specified graph.
     * NOTE : Recursive method used to find path...
     *
     * @param array $mazeGraph map of MazeGraphItem with TMDB identifier as key and MazeGraphItem as value
     * @param MazeGraphItem $currentItem mazeGraphItem to use as starting point to find longuest path
     * @param array $currentPath current path of MazeGraphItem (path we are building recursively)
     *
     * @return array array of MazeGraphItem matching longest path found
     */
    protected function findLongestPath(array $mazeGraph, MazeGraphItem $currentItem, array $currentPath): array
    {
        // If current actor already used in current path => stop here
        if (in_array($currentItem, $currentPath)) {
            return $currentPath;
        }

        // Update current path
        $currentPath[] = $currentItem;
        $longestPath = null;

        // Browse each linked item and keep building path for a longer one
        // NOTE : Linked items list should never be empty
        foreach ($currentItem->getLinkedItems() as $linkedItem) {
            $newPath = $this->findLongestPath($mazeGraph, $linkedItem, $currentPath);

            // If new path is longer than previous path => use it as longest path
            if (count($newPath) > count($longestPath)) {
                $longestPath = $newPath;
            }
        }

        // NOTE : This condition should never occurs (item should always be linked to another one...)
        if (null === $longestPath) {
            $longestPath = $currentPath;
        }

        return $longestPath;
    }
}
