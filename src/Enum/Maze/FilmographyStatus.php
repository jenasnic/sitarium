<?php

namespace App\Enum\Maze;

class FilmographyStatus
{
    const UNINITIALIZED = 'filmography_to_check';
    const INITIALIZED = 'filmography_validated';
    const EMPTY = 'filmography_empty';

    /**
     * @return array
     */
    public static function getAll(): array
    {
        return [
            self::UNINITIALIZED,
            self::INITIALIZED,
            self::EMPTY,
        ];
    }

    /**
     * @param string $status
     *
     * @return bool
     */
    public static function exists(string $status): bool
    {
        return in_array($status, self::getAll());
    }
}
