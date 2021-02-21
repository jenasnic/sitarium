<?php

namespace App\Validator\Tmdb;

use App\Model\Tmdb\Movie;

class TaglineMovieValidator implements TmdbValidatorInterface
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
        // If no release date or not yet rated => ignore movie
        if (!$movie->getReleaseDate() || new \DateTime() < $movie->getReleaseDate()) {
            return false;
        }

        // If no picture => ignore movie
        if (null === $movie->getPosterPath()) {
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

        return true;
    }
}
