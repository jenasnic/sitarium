<?php

namespace App\Validator\Tmdb;

use App\Model\Tmdb\Actor;

/**
 * @implements TmdbValidatorInterface<Actor>
 */
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
        if (empty($actor->getProfilePath())) {
            return false;
        }

        return true;
    }
}
