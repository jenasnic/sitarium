<?php

namespace App\Service\Action;

use App\Model\Maze\MovieGraphItem;

/**
 * This class allows to build the longest path linking movies using common actors.
 * NOTE : Use graph built using MovieGraphBuilder
 * @see MovieGraphBuilder
 */
class MaxMoviePathAction
{
    /**
     * Allows to build the largest array with specified movies linked together with common actors.
     *
     * @param array $movieGraph Map of MovieGraphItem with TMDB identifier as key and MovieGraphItem as value.
     *
     * @throws \Exception Throw exception if not enough movie in specified movie graph...
     *
     * @return array Array of movies linked with common actors.
     */
    public function getPath(array $movieGraph): array
    {
        if (count($movieGraph) < 2) {
            throw new \Exception('Not enough movie in specified graph. Set a movie graph with at least 2 movies.');
        }

        // Browse all MovieGraphItem randomly (for a same graph we can get different path...)
        $tmdbIdList = array_keys($movieGraph);
        shuffle($tmdbIdList);

        $longestPath = [];
        foreach ($tmdbIdList as $tmdbId) {
            $graphItem = $movieGraph[$tmdbId];
            $path = [];
            $path = $this->findLongestPath($movieGraph, $graphItem, $path);

            // If longer path found from current movie => keep it
            if (count($path) > count($longestPath)) {
                $longestPath = $path;
            }
        }

        // Convert result as movie list (as Movie)
        $result = [];
        foreach ($longestPath as $graphItem) {
            $result[] = $graphItem->getMovie();
        }

        return $result;
    }

    /**
     * Allows to find longuest path between movies. It is used to link a maximum of movies among movie graph.
     * NOTE : Recursive method used to find path...
     *
     * @param array $movieGraph Map of MovieGraphItem with TMDB identifier as key and MovieGraphItem as value.
     * @param MovieGraphItem $currentMovie MovieGraphItem to use as starting point to find longuest path.
     * @param array $currentPath Current path of MovieGraphItem (path we are building recursively).
     *
     * @return array Array of MovieGraphItem matching longest path found.
     */
    protected function findLongestPath(array $movieGraph, MovieGraphItem $currentMovie, array $currentPath): array
    {
        // If current movie already used in current path => stop here
        if (in_array($currentMovie, $currentPath)) {
            return $currentPath;
        }

        // Update current path
        $currentPath[] = $currentMovie;
        $longestPath = null;

        // Browse each linked movie and keep building path for a longer one
        // NOTE : Linked movies list should never be empty
        foreach ($currentMovie->getLinkedMovies() as $linkedMovie) {
            $newPath = $this->findLongestPath($movieGraph, $linkedMovie, $currentPath);

            // If new path is longer than previous path => use it as longest path
            if (count($newPath) > count($longestPath)) {
                $longestPath = $newPath;
            }
        }

        // NOTE : This condition should never occurs (movie should always be linked to another movie...)
        if ($longestPath == null) {
            $longestPath = $currentPath;
        }

        return $longestPath;
    }
}
