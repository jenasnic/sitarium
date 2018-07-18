<?php

namespace App\Validator;

/**
 * Allows to implement validation methods used when searching enities in TMDB => useful to filter result items.
 */
interface TmdbValidator
{
    /**
     * @param mixed $entity
     *
     * @return bool
     */
    public function isValid($entity): bool;
}
