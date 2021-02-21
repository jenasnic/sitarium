<?php

namespace App\Service\Tmdb\Adapter;

use App\Model\Tmdb\DisplayableItem;
use App\Model\Tmdb\Movie;

class TmdbMovieAdapter implements DisplayableAdapterInterface
{
    /**
     * @param Movie $item
     */
    public function adapt($item): DisplayableItem
    {
        return new DisplayableItem($item->getTmdbId(), $item->getTitle(), $item->getPosterPath());
    }

    public function support($item): bool
    {
        return $item instanceof Movie;
    }
}
