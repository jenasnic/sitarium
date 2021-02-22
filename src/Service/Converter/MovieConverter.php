<?php

namespace App\Service\Converter;

use App\Entity\Maze\Movie;
use App\Model\Tmdb\Movie as TmdbMovie;

class MovieConverter
{
    public function convert(TmdbMovie $movie): Movie
    {
        $entity = new Movie();

        $entity->setTmdbId($movie->getId());
        $entity->setTitle($movie->getTitle());
        $entity->setPictureUrl($movie->getProfilePath());
        $entity->setReleaseDate($movie->getReleaseDate());

        return $entity;
    }
}
