<?php

namespace App\Service\Action;

use App\Entity\Maze\Movie;
use App\Model\Maze\MovieGraphItem;

/**
 * This class allows to build the shortest path between movies using common actors.
 * NOTE : Use graph built using MovieGraphBuilder
 * @see MovieGraphBuilder
 */
class MinMoviePathAction
{
    /**
     * Allows to find shortest path between two specified movies using common actors between movies.
     *
     * @param array $movieGraph Map of MovieGraphItem with TMDB identifier as key and MovieGraphItem as value.
     * @param Movie $movie1 First movie we want to find the shortest path to second movie.
     * @param Movie $movie2 Second movie we want to find the shortest path from first movie.
     */
    public function getPath(array $movieGraph, Movie $movie1, Movie $movie2)
    {
        $currentPath = [];
        $currentMovie = $movieGraph[$movie1->getTmdbId()];
        $movieToReach = $movieGraph[$movie2->getTmdbId()];

        $shortestPath = $this->findShortestPath($movieGraph, $currentMovie, $movieToReach, $currentPath);

        // If we have found a path between movies => return it as array of movies
        if ($shortestPath) {
            $result = [];
            foreach ($shortestPath as $graphItem) {
                $result[] = $graphItem->getMovie();
            }

            return $result;
        } else {
            return null;
        }
    }

    /**
     * Allows to find shortest path between movies.
     * NOTE : Recursive method used to find path...
     *
     * @param array $movieGraph Map of MovieGraphItem with TMDB identifier as key and MovieGraphItem as value.
     * @param MovieGraphItem $currentMovie MovieGraphItem to use as starting point to find shortest path.
     * @param MovieGraphItem $movieToReach MovieGraphItem to use as ending point to find shortest path.
     * @param array $currentPath Current path of MovieGraphItem (path we are building recursively).
     *
     * @return array Array of MovieGraphItem matching shortest path or NULL if no path found.
     */
    protected function findShortestPath(array $movieGraph, MovieGraphItem $currentMovie, MovieGraphItem $movieToReach, array $currentPath): array
    {
        // If path already found => current path must be better
        if ($currentMovie->getBestPathSize() > 0 && count($currentPath) >= $currentMovie->getBestPathSize()) {
            return null;
        }

        // If movie reached => stop here (path OK)
        if ($currentMovie === $movieToReach) {
            $currentPath[] = $currentMovie;
            return $currentPath;
        }

        // If current movie already used in current path => stop here (wrong path)
        if (in_array($currentMovie, $currentPath)) {
            return null;
        }

        // If current movie already used in a matching path and current path already longer => stop here (path will be too long)
        if ($currentMovie->getBestPathPosition() > 0 && count($currentPath) >= $currentMovie->getBestPathPosition()) {
            return null;
        }

        // If path already found and current movie already used in a matching path with longer path => stop here (path will be too long)
        if ($currentMovie->getBestPathSize() > 0
            && $currentMovie->getBestPathPosition() > 0
            && $currentMovie->getBestPathPosition() >= $currentMovie->getBestPathSize()
        ) {
            return null;
        }

        // If path still OK => add curent movie and process linked movies...
        $currentPath[] = $currentMovie;

        // If movie to reach exist in linked movies => use it
        if (in_array($movieToReach, $currentMovie->getLinkedMovies())) {
            $currentPath[] = $movieToReach;
            return $currentPath;
        }

        $minPath = null;
        foreach ($currentMovie->getLinkedMovies() as $linkedMovie) {
            $newPath = $this->findShortestPath($movieGraph, $linkedMovie, $movieToReach, $currentPath);

            // If path found => check if OK
            if (null !== $newPath) {
                // If no best path found yet or better path found
                if (0 === $currentMovie->getBestPathSize() || count($newPath) <= $currentMovie->getBestPathSize()) {
                    $this->setBestPathSizeForGraphItem($movieGraph, count($newPath));
                    $minPath = $newPath;
                    $this->updateBestPathPositionForGraphItem($newPath);
                }
            }
        }

        return $minPath;
    }

    /**
     * @param array $movieGraph
     * @param int $bestPathSize
     */
    private function setBestPathSizeForGraphItem(array $movieGraph, int $bestPathSize)
    {
        /** @var MovieGraphItem $movieGraphItem */
        foreach ($movieGraph as $movieGraphItem) {
            $movieGraphItem->setBestPathSize($bestPathSize);
        }
    }

    /**
     * @param array $moviePath
     */
    private function updateBestPathPositionForGraphItem(array $moviePath)
    {
        for ($i = 0; $i < count($moviePath); $i++) {
            if (0 === $moviePath[$i]->getBestPathPosition() || $moviePath[$i]->getBestPathPosition() > $i) {
                $moviePath[$i]->setBestPathPosition($i);
            }
        }
    }
}
