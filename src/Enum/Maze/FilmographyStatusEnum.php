<?php

namespace App\Enum\Maze;

class FilmographyStatusEnum
{
    public const UNINITIALIZED = 'filmography_to_check';
    public const INITIALIZED = 'filmography_validated';
    public const EMPTY = 'filmography_empty';

    public static function getAll(): array
    {
        return [
            self::UNINITIALIZED,
            self::INITIALIZED,
            self::EMPTY,
        ];
    }

    public static function exists(string $status): bool
    {
        return in_array($status, self::getAll());
    }
}
