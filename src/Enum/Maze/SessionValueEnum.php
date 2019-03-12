<?php

namespace App\Enum\Maze;

class SessionValueEnum
{
    // Session variable used to store progress state when building casting
    const SESSION_BUILD_CASTING_PROGRESS = 'session_build_casting_progress';
    const SESSION_BUILD_CASTING_TOTAL = 'session_build_casting_total';

    // Session variable used to store progress state when building filmography
    const SESSION_BUILD_FILMOGRAPHY_PROGRESS = 'session_build_filmography_progress';
    const SESSION_BUILD_FILMOGRAPHY_TOTAL = 'session_build_filmography_total';

    /**
     * @return array
     */
    public static function getAll(): array
    {
        return [
            self::SESSION_BUILD_CASTING_PROGRESS,
            self::SESSION_BUILD_CASTING_TOTAL,
            self::SESSION_BUILD_FILMOGRAPHY_PROGRESS,
            self::SESSION_BUILD_FILMOGRAPHY_TOTAL,
        ];
    }
}
