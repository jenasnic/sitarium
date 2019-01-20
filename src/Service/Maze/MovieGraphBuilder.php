<?php

namespace App\Service\Maze;

use App\Model\Maze\MazeGraphItem;
use App\Repository\Maze\MovieRepository;

/**
 * This class allows to build movies graph (i.e. list of movies with all links between them through common actors).
 */
class MovieGraphBuilder
{
    /**
     * @var MovieRepository
     */
    protected $movieRespository;

    /**
     * @param MovieRepository $movieRespository
     */
    public function __construct(MovieRepository $movieRespository)
    {
        $this->movieRespository = $movieRespository;
    }

    /**
     * Allows to build a graph with movies and links between them.
     * This graph is returned as a map with an entry for each point of the graph (i.e. each movie).
     * => Entry point of the graph (representing a movie) can be reached using TMDB identifier of movie.
     *
     * @param int[]|array|null $movieIds Array of TMDB identifiers for movies to use to build graph.
     * Default value null means that we build full graph for all existing movies.
     *
     * @return array map of MazeGraphItem with TMDB identifier as key and MazeGraphItem as value
     */
    public function buildGraph(?array $movieIds = null): array
    {
        $movieMap = [];
        $mazeGraphItemMap = [];

        $movieList = (null === $movieIds)
            ? $this->movieRespository->findAll()
            : $this->movieRespository->findBy(['tmdbId' => $movieIds])
        ;

        // First step : Build movie map with tmdbId as key and matching movie as value
        foreach ($movieList as $movie) {
            $movieMap[$movie->getTmdbId()] = $movie;
        }

        // Second step : get all linked movies array
        $linkedMoviesIds = $this->movieRespository->getLinkedMoviesIds($movieIds);

        // Third step : build graph with movies
        foreach ($linkedMoviesIds as $moviesIds) {
            $mainMovieId = $moviesIds['main_movie_identifier'];
            $linkedMovieId = $moviesIds['linked_movie_identifier'];

            // Check if movies are already set in mazeGraphItemMap
            if (!isset($mazeGraphItemMap[$mainMovieId])) {
                $mazeGraphItemMap[$mainMovieId] = new MazeGraphItem($movieMap[$mainMovieId]);
            }
            if (!isset($mazeGraphItemMap[$linkedMovieId])) {
                $mazeGraphItemMap[$linkedMovieId] = new MazeGraphItem($movieMap[$linkedMovieId]);
            }

            $mainMovieGraphItem = $mazeGraphItemMap[$mainMovieId];
            $linkedMovieGraphItem = $mazeGraphItemMap[$linkedMovieId];

            // Update main movie (as MovieGraphItem) if needed (add linked movie if not already added)
            if (!in_array($linkedMovieGraphItem, $mainMovieGraphItem->getLinkedItems())) {
                $mainMovieGraphItem->addLinkedItem($linkedMovieGraphItem);
            }
        }

        return $mazeGraphItemMap;
    }
}
