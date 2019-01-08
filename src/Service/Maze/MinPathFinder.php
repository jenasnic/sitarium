<?php

namespace App\Service\Maze;

use App\Entity\Maze\Actor;
use App\Entity\Maze\Movie;
use App\Model\Maze\MazeGraphItem;

/**
 * This class allows to build the shortest path between 2 items of specified graph.
 * NOTE : Use graph built using ActorGraphBuilder or MovieGraphBuilder.
 *
 * @see ActorGraphBuilder
 * @see MovieGraphBuilder
 */
class MinPathFinder
{
    /**
     * Allows to find shortest path between two specified grah items.
     *
     * @param array $mazeGraph map of MazeGraphItem with TMDB identifier as key and MazeGraphItem as value
     * @param MazeGraphItem $startItem mazeGraphItem to use as starting point to find shortest path
     * @param MazeGraphItem $endItem mazeGraphItem to use as ending point to find shortest path
     *
     * @return Actor[]|Movie[]|array|null array of items linked together
     */
    public function find(array $mazeGraph, MazeGraphItem $startItem, MazeGraphItem $endItem): ?array
    {
        $currentPath = [];
        $shortestPath = $this->findShortestPath($mazeGraph, $startItem, $endItem, $currentPath);

        // If we have found a path matching specified parameters => Convert result as item list (as Actor or Movie)
        if ($shortestPath) {
            $result = [];
            foreach ($shortestPath as $graphItem) {
                $result[] = $graphItem->getItem();
            }

            return $result;
        } else {
            return null;
        }
    }

    /**
     * Allows to find shortest path between items.
     * NOTE : Recursive method used to find path...
     *
     * @param array $mazeGraph map of MazeGraphItem with TMDB identifier as key and MazeGraphItem as value
     * @param MazeGraphItem $currentItem mazeGraphItem to use as starting point to find shortest path
     * @param MazeGraphItem $itemToReach mazeGraphItem to use as ending point to find shortest path
     * @param MazeGraphItem[]|array $currentPath current path of MazeGraphItem (path we are building recursively)
     *
     * @return MazeGraphItem[]|array|null array of MazeGraphItem matching shortest path or NULL if no path found
     */
    protected function findShortestPath(array $mazeGraph, MazeGraphItem $currentItem, MazeGraphItem $itemToReach, array $currentPath): ?array
    {
        // If path already found => current path must be better
        if ($currentItem->getBestPathSize() > 0 && count($currentPath) >= $currentItem->getBestPathSize()) {
            return null;
        }

        // If item reached => stop here (path OK)
        if ($currentItem === $itemToReach) {
            $currentPath[] = $currentItem;

            return $currentPath;
        }

        // If current item already used in current path => stop here (wrong path)
        if (in_array($currentItem, $currentPath)) {
            return null;
        }

        // If current item already used in a matching path and current path already longer => stop here (path will be too long)
        if ($currentItem->getBestPathPosition() > 0 && count($currentPath) >= $currentItem->getBestPathPosition()) {
            return null;
        }

        // If path already found and current item already used in a matching path with longer path => stop here (path will be too long)
        if ($currentItem->getBestPathSize() > 0
            && $currentItem->getBestPathPosition() > 0
            && $currentItem->getBestPathPosition() >= $currentItem->getBestPathSize()
        ) {
            return null;
        }

        // If path still OK => add curent item and process linked items...
        $currentPath[] = $currentItem;

        // If item to reach exist in linked items => use it
        if (in_array($itemToReach, $currentItem->getLinkedItems())) {
            $currentPath[] = $itemToReach;

            return $currentPath;
        }

        $minPath = null;
        foreach ($currentItem->getLinkedItems() as $linkedItem) {
            $newPath = $this->findShortestPath($mazeGraph, $linkedItem, $itemToReach, $currentPath);

            // If path found => check if OK
            if (null !== $newPath) {
                // If no best path found yet or better path found
                if (0 === $currentItem->getBestPathSize() || count($newPath) <= $currentItem->getBestPathSize()) {
                    $this->setBestPathSizeForGraphItem($mazeGraph, count($newPath));
                    $minPath = $newPath;
                    $this->updateBestPathPositionForGraphItem($newPath);
                }
            }
        }

        return $minPath;
    }

    /**
     * @param array $mazeGraph
     * @param int $bestPathSize
     */
    private function setBestPathSizeForGraphItem(array $mazeGraph, int $bestPathSize): void
    {
        /** @var MazeGraphItem $mazeGraphItem */
        foreach ($mazeGraph as $mazeGraphItem) {
            $mazeGraphItem->setBestPathSize($bestPathSize);
        }
    }

    /**
     * @param MazeGraphItem[]|array $itemPath
     */
    private function updateBestPathPositionForGraphItem(array $itemPath): void
    {
        for ($i = 0; $i < count($itemPath); ++$i) {
            if (0 === $itemPath[$i]->getBestPathPosition() || $itemPath[$i]->getBestPathPosition() > $i) {
                $itemPath[$i]->setBestPathPosition($i);
            }
        }
    }
}
