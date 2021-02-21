<?php

namespace App\Service\Tmdb\Adapter;

use App\Model\Tmdb\Actor;
use App\Model\Tmdb\DisplayableItem;

class TmdbActorAdapter implements DisplayableAdapterInterface
{
    /**
     * @param Actor $item
     */
    public function adapt($item): DisplayableItem
    {
        return new DisplayableItem($item->getId(), $item->getName(), $item->getProfilePath());
    }

    public function support($item): bool
    {
        return $item instanceof Actor;
    }
}
