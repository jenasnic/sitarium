<?php

namespace App\Validator\Tmdb;

/**
 * Allows to implement validation methods used when searching enities in TMDB => useful to filter result items.
 *
 * @psalm-template T
 */
interface TmdbValidatorInterface
{
    /**
     * @psalm-param T $entity
     *
     * @param mixed $entity
     */
    public function isValid($entity): bool;
}
