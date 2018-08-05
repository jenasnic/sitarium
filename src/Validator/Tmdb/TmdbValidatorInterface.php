<?php

namespace App\Validator\Tmdb;

/**
 * Allows to implement validation methods used when searching enities in TMDB => useful to filter result items.
 */
interface TmdbValidatorInterface
{
    /**
     * @param mixed $entity
     *
     * @return bool
     */
    public function isValid($entity): bool;
}
