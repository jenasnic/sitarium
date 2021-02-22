<?php

namespace App\Service\Converter;

use App\Entity\Maze\Actor;
use App\Model\Tmdb\Actor as TmdbActor;

class ActorConverter
{
    public function convert(TmdbActor $actor): Actor
    {
        $entity = new Actor();

        $entity->setTmdbId($actor->getId());
        $entity->setFullname($actor->getName());
        $entity->setBirthdate($actor->getBirthday());
        $entity->setPictureUrl($actor->getProfilePath());

        return $entity;
    }
}
