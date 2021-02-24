<?php

namespace App\Service\Tmdb\Adapter;

use App\Entity\Maze\FilmographyMovie;
use App\Entity\Maze\Movie;
use App\Entity\Tagline\Movie as TaglineMovie;
use App\Model\Tmdb\DisplayableItem;

/**
 * @implements DisplayableAdapterInterface<FilmographyMovie>
 */
class EntityMovieAdapter implements DisplayableAdapterInterface
{
    /**
     * @param Movie|FilmographyMovie|TaglineMovie $item
     */
    public function adapt($item): DisplayableItem
    {
        return new DisplayableItem($item->getTmdbId(), $item->getTitle(), $item->getPictureUrl());
    }

    public function support($item): bool
    {
        return $item instanceof Movie
            || $item instanceof FilmographyMovie
            || $item instanceof TaglineMovie
        ;
    }
}
