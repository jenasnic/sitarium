<?php

namespace App\Service\Tmdb\Adapter;

use App\Model\Tmdb\DisplayableItem;
use App\Model\Tmdb\Movie;

/**
 * @implements DisplayableAdapterInterface<Movie>
 */
class TmdbMovieAdapter implements DisplayableAdapterInterface
{
    /**
     * @param Movie $item
     */
    public function adapt($item): DisplayableItem
    {
        return new DisplayableItem($item->getId(), $item->getTitle(), $item->getPosterPath());
    }

    public function support($item): bool
    {
        return $item instanceof Movie;
    }
}
