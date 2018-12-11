<?php

namespace App\Validator\Maze;

use App\Entity\Maze\Actor;
use App\Validator\Tmdb\TmdbValidatorInterface;

class ActorValidator implements TmdbValidatorInterface
{
    /**
     * Allows to check if specified actor is valid or not.
     * NOTE : Keep only actors with picture.
     *
     * @param Actor $actor actor to check
     *
     * @return bool TRUE if actor is valid, FALSE either
     */
    public function isValid($actor): bool
    {
        if (empty($actor->getPictureUrl())) {
            return false;
        }

        return true;
    }
}
