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
}
