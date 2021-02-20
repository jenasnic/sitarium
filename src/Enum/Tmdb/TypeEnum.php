<?php

namespace App\Enum\Tmdb;

class TypeEnum
{
    const ACTOR = 'actor';
    const MOVIE = 'movie';

    /**
     * @return array
     */
    public static function getAll(): array
    {
        return [
            self::ACTOR,
            self::MOVIE,
        ];
    }

    /**
     * @return bool
     */
    public static function exist(string $value): bool
    {
        return in_array($value, self::getAll());
    }
}
