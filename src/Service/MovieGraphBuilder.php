<?php

namespace App\Service;

use App\Entity\Maze\Movie;
use App\Model\Maze\MovieGraphItem;
use Doctrine\ORM\EntityManagerInterface;

/**
 * This class allows to build movies graph (i.e. list of movies with all links between them through common actors).
 */
class MovieGraphBuilder
{
    /**
     * @var EntityManagerInterface
     */
    protected $entityManager;

    /**
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * Allows to build a graph with movies and links between them.
     * This graph is returned as a map with an entry for each point of the graph (i.e. each movie).
     * => Entry point of the graph (representing a movie) can be reach using TMDB identifier of movie.
     *
     * @param array $movieIds Array of TMDB identifiers (as integer) for movies to use to build graph.
     * Default value null means that we build full graph for all existing movies.
     *
     * @return array Map of MovieGraphItem with TMDB identifier as key and MovieGraphItem as value.
     */
    public function buildGraph($movieIds = null): array
    {
        $movieMap = [];
        $movieGraphItemMap = [];

        $movieList = (null === $movieIds)
            ? $this->entityManager->getRepository(Movie::class)->findAll()
            : $this->entityManager->getRepository(Movie::class)->findBy(array('tmdbId' => $movieIds))
        ;

        // First step : Build movie map with tmdbId as key and matching movie as value
        foreach ($movieList as $movie) {
            $movieMap[$movie->getTmdbId()] = $movie;
        }

        // Second step : get all linked movies array
        $linkedMovieList = $this->entityManager->getRepository(Movie::class)->getLinkedMoviesId($movieIds);

        // Third step : build graph with movies
        foreach ($linkedMovieList as $linkedMovieIds) {
            $mainMovieId = $linkedMovieIds['main_movie_identifier'];
            $linkedMovieId = $linkedMovieIds['linked_movie_identifier'];

            // Check if movies are already set in movieGraphItemMap
            if (!isset($movieGraphItemMap[$mainMovieId])) {
                $movieGraphItemMap[$mainMovieId] = new MovieGraphItem($movieMap[$mainMovieId]);
            }
            if (!isset($movieGraphItemMap[$linkedMovieId])) {
                $movieGraphItemMap[$linkedMovieId] = new MovieGraphItem($movieMap[$linkedMovieId]);
            }

            $mainMovieGraphItem = $movieGraphItemMap[$mainMovieId];
            $linkedMovieGraphItem = $movieGraphItemMap[$linkedMovieId];

            // Update main movie (as MovieGraphItem) if needed (add linked movie if not already added)
            if (!in_array($linkedMovieGraphItem, $mainMovieGraphItem->getLinkedMovies()))
                $mainMovieGraphItem->addLinkedActor($linkedMovieGraphItem);
        }

        return $movieGraphItemMap;
    }
}
