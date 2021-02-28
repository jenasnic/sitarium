<?php

namespace App\Service\Tmdb\Adapter;

use App\Entity\Maze\Actor;
use App\Entity\Maze\CastingActor;
use App\Model\Tmdb\DisplayableItem;

/**
 * @implements DisplayableAdapterInterface<CastingActor|Actor>
 */
class EntityActorAdapter implements DisplayableAdapterInterface
{
    /**
     * @param Actor|CastingActor $item
     */
    public function adapt($item): DisplayableItem
    {
        return new DisplayableItem($item->getTmdbId(), $item->getFullname(), $item->getPictureUrl());
    }

    public function support($item): bool
    {
        return $item instanceof Actor || $item instanceof CastingActor;
    }
}
