<?php

namespace App\Enum\Maze;

class CastingStatus
{
    const UNINITIALIZED = 'credit_to_check';
    const INITIALIZED = 'credit_validated';
    const EMPTY = 'credit_empty';

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
