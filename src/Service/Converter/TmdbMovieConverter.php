<?php

namespace App\Service\Converter;

use App\Model\Tmdb\Movie as TmdbMovie;
use App\Entity\Maze\Movie;

class TmdbMovieConverter
{
    public function convert(TmdbMovie $movie)
    {
        $entity = new Movie();

        $entity->setTmdbId($movie->getId());
        $entity->setTitle($movie->getTitle());
        $entity->setPictureUrl($movie->getProfilePath());

        return $entity;
    }
}
