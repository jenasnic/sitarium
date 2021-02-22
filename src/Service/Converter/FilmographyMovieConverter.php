<?php

namespace App\Service\Converter;

use App\Entity\Maze\FilmographyMovie;
use App\Model\Tmdb\Movie as TmdbMovie;

class FilmographyMovieConverter
{
    public function convert(TmdbMovie $movie): FilmographyMovie
    {
        $entity = new FilmographyMovie();

        $entity->setTmdbId($movie->getId());
        $entity->setTitle($movie->getTitle());
        $entity->setPictureUrl($movie->getProfilePath());
        $entity->setReleaseDate($movie->getReleaseDate());
        $entity->setVoteCount($movie->getVoteCount());

        return $entity;
    }
}
