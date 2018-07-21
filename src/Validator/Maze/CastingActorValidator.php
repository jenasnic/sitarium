<?php

namespace App\Validator\Maze;

use App\Validator\TmdbValidator;
use App\Entity\Maze\Actor;

class CastingActorValidator implements TmdbValidator
{
    /**
     * Allows to check if specified actor is valid or not.
     * NOTE : Keep only actors with picture.
     *
     * @param Actor $actor actor to check.
     *
     * @return bool TRUE if actor is valid, FALSE either.
     */
    public function isValid($actor): bool
    {
        // If no picture => ignore actor
        if (empty($actor->getPictureUrl())) {
            return false;
        }

        // If actor is not credited => ignore actor
        if (strpos($actor->getCharacter(), '(uncredited)') !== false) {
            return false;
        }

        return true;
    }
}