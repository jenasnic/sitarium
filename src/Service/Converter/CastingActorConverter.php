<?php

namespace App\Service\Converter;

use App\Entity\Maze\CastingActor;
use App\Model\Tmdb\Actor as TmdbActor;

class CastingActorConverter
{
    public function convert(TmdbActor $actor): CastingActor
    {
        $entity = new CastingActor();

        $entity->setTmdbId($actor->getId());
        $entity->setFullname($actor->getName());
        $entity->setCharacter($actor->getCharacter());
        $entity->setPictureUrl($actor->getProfilePath());

        return $entity;
    }
}
