<?php

namespace App\Enum\Maze;

class CastingStatusEnum
{
    const UNINITIALIZED = 'casting_to_check';
    const INITIALIZED = 'casting_validated';
    const EMPTY = 'casting_empty';

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
