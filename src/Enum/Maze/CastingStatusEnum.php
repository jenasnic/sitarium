<?php

namespace App\Enum\Maze;

class CastingStatusEnum
{
    public const UNINITIALIZED = 'casting_to_check';
    public const INITIALIZED = 'casting_validated';
    public const EMPTY = 'casting_empty';

    /**
     * @return array<string>
     */
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
