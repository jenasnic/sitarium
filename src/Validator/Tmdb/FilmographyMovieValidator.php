<?php

namespace App\Validator\Tmdb;

use App\Model\Tmdb\Movie;
use DateTime;

/**
 * @implements TmdbValidatorInterface<Movie>
 */
class FilmographyMovieValidator implements TmdbValidatorInterface
{
    /**
     * Allows to check if specified movie is valid or not.
     *
     * @param Movie $movie movie to check
     *
     * @return bool TRUE if movie is valid, FALSE either
     */
    public function isValid($movie): bool
    {
        if (empty($movie->getPosterPath())) {
            return false;
        }

        if (!$movie->getReleaseDate()) {
            return false;
        }

        // If movie not yet rated => ignore movie
        if (new DateTime() < $movie->getReleaseDate()) {
            return false;
        }

        // If documentary => ignore movie
        if (in_array(99, $movie->getGenreIds())) {
            return false;
        }

        // If poor vote count (<100) => ignore movie
        if ($movie->getVoteCount() < 100) {
            return false;
        }

        // If actor is not credited => ignore movie
        if (false !== strpos($movie->getCharacter(), '(uncredited)')) {
            return false;
        }

        return true;
    }
}
