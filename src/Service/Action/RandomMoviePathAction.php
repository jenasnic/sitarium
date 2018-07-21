<?php

namespace App\Service\Action;

use App\Model\Maze\MovieGraphItem;

/**
 * This class allows to build a kind of maze between movies linked together with common actors.
 * NOTE : Use graph built using MovieGraphBuilder
 * @see MovieGraphBuilder
 */
class RandomMoviePathAction
{
    /**
     * Allows to build an array with movies linked together with common casting actors.
     *
     * @param array $movieGraph Map of MovieGraphItem with TMDB identifier as key and MovieGraphItem as value.
     * @param int $mazeSize Number of linked movies we want to find (at least 2).

     * @throws \Exception Throw exception if specified size doesn't allow to build list of movies...
     *
     * @return array Array of movies linked with common actors (size of array is 'mazeSize').
     */
    public function getPath(array $movieGraph, int $mazeSize)
    {
        if ($mazeSize < 2) {
            throw new \Exception('Movie count must be equal or greater than 2.');
        }

        if ($mazeSize > count($movieGraph)) {
            throw new \Exception('Movie count is too large to build a path. Choose a smaller movie count size or increase movies list.');
        }

        // Browse all MovieGraphItem randomly and try to find a path with specified size
        $tmdbIdList = array_keys($movieGraph);
        shuffle($tmdbIdList);

        foreach ($tmdbIdList as $tmdbId) {
            $graphItem = $movieGraph[$tmdbId];

            $path = [];
            $path = $this->findPathWithSize($movieGraph, $graphItem, $path, $mazeSize);

            // If we have found a path matching specified parameters => return path as array of movies
            if (count($path) == $mazeSize) {
                $result = [];
                foreach ($path as $graphItem) {
                    $result[] = $graphItem->getMovie();
                }

                return $result;
            }
        }

        //throw new \Exception('Unable to find path for specified size. Choose a smaller movie count or increase movies list.');
        return null;
    }

    /**
     * Allows to build path with MovieGraphItem depending on specified parameter.
     * NOTE : Recursive method used to build path...
     *
     * @param array $movieGraph Map of MovieGraphItem with TMDB identifier as key and MovieGraphItem as value.
     * @param MovieGraphItem $graphItem ActorGraphItem to use to build path (as new path step).
     * @param array $currentPath Current path of MovieGraphItem (path we are building recursively).
     * @param int $pathSize Size of path (for previous argument $currentPath) we want to get (i.e. movie count).
     *
     * @return array Array of MovieGraphItem matching current built path.
     */
    protected function findPathWithSize(array $movieGraph, MovieGraphItem $graphItem, array $currentPath, int $pathSize): array
    {
        // If specified MovieGraphItem already used in current path => stop here
        if (in_array($graphItem, $currentPath)) {
            return $currentPath;
        }

        // In other case, add specified item to current path
        $currentPath[] = $graphItem;

        // If current path reached required size => stop here
        if (count($currentPath) == $pathSize) {
            return $currentPath;
        }

        // Else keep building current path with linked movies of specified maze item.
        // NOTE : Browse linked movies randomly
        $randomLinkedMovies = $this->shuffleArray($graphItem->getLinkedMovies());
        foreach ($randomLinkedMovies as $linkedGraphItem) {
            $newPath = $this->findPathWithSize($movieGraph, $linkedGraphItem, $currentPath, $pathSize);
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
