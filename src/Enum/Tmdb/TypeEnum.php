<?php

namespace App\Enum\Tmdb;

class TypeEnum
{
    public const ACTOR = 'actor';
    public const MOVIE = 'movie';

    public static function getAll(): array
    {
        return [
            self::ACTOR,
            self::MOVIE,
        ];
    }

    public static function exist(string $value): bool
    {
        return in_array($value, self::getAll());
    }
}
