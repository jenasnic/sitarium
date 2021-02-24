<?php

namespace App\Service\Converter;

use App\Entity\Tagline\Movie;
use App\Model\Tmdb\Movie as TmdbMovie;

class TaglineMovieConverter
{
    public function convert(TmdbMovie $movie): Movie
    {
        $entity = new Movie();

        $entity->setTmdbId($movie->getId());
        $entity->setTitle($movie->getTitle());
        $entity->setReleaseDate($movie->getReleaseDate());
        $entity->setPictureUrl($movie->getPosterPath());
        $entity->setTagline($movie->getTagline());

        return $entity;
    }
}
