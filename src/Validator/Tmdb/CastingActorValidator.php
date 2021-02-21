<?php

namespace App\Validator\Tmdb;

use App\Model\Tmdb\Actor;

class CastingActorValidator implements TmdbValidatorInterface
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
        // If no picture => ignore actor
        if (empty($actor->getProfilePath())) {
            return false;
        }

        // If actor is not credited => ignore actor
        if (false !== strpos($actor->getCharacter(), '(uncredited)')) {
            return false;
        }

        return true;
    }
}
