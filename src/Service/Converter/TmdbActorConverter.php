<?php

namespace App\Service\Converter;

use App\Model\Tmdb\Actor as TmdbActor;
use App\Entity\Maze\Actor;

class TmdbActorConverter
{
    public function convert(TmdbActor $actor)
    {
        $entity = new Actor();

        $entity->setTmdbId($actor->getId());
        $entity->setFullname($actor->getName());
        $entity->setBirthdate($actor->getBirthday());
        $entity->setPictureUrl($actor->getProfilePath());

        return $entity;
    }
}
