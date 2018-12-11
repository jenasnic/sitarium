<?php

namespace App\Service\Maze;

use App\Entity\Maze\FilmographyMovie;
use App\Repository\Maze\ActorRepository;
use App\Repository\Maze\FilmographyMovieRepository;
use App\Tool\TextUtil;

/**
 * This class allows to check if given response to link both actors (in actor path) is valide or not.
 * => Check if given movie title match with one of common movies between both actors.
 */
class ActorPathResponseValidator
{
    /**
     * @var ActorRepository
     */
    protected $actorRepository;

    /**
     * @var FilmographyMovieRepository
     */
    protected $movieRepository;

    /**
     * @param ActorRepository $actorRepository
     * @param FilmographyMovieRepository $movieRepository
     */
    public function __construct(ActorRepository $actorRepository, FilmographyMovieRepository $movieRepository)
    {
        $this->actorRepository = $actorRepository;
        $this->movieRepository = $movieRepository;
    }

    /**
     * Allows to check if both specified actors define common movie matching specified movie title.
     *
     * @param int $actorId1
     * @param int $actorId2
     * @param string $movieTitle
     *
     * @return FilmographyMovie|null common movie matching specified title or null if no common movie found
     */
    public function check(int $actorId1, int $actorId2, string $movieTitle): ?FilmographyMovie
    {
        $actor1 = $this->actorRepository->find($actorId1);
        $actor2 = $this->actorRepository->find($actorId2);

        $commonMovies = array_uintersect(
            $actor1->getMovies()->toArray(),
            $actor2->getMovies()->toArray(),
            function (FilmographyMovie $movie1, FilmographyMovie $movie2) {
                return $movie1->getTmdbId() - $movie2->getTmdbId();
            }
        );

        // Check if specified movie title match with one of common movies
        $movieTitleToCompare = TextUtil::sanitize($movieTitle);
        foreach ($commonMovies as $movie) {
            if ($movieTitleToCompare === TextUtil::sanitize($movie->getTitle())) {
                return $movie;
            }
        }

        return null;
    }
}
