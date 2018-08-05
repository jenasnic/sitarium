<?php

namespace App\Enum\Quiz;

class SessionValues
{
    // Session variable used to store progress state when building tmdb link
    const SESSION_BUILD_TMDB_LINK_PROGRESS = 'session_build_tmdb_link_progress';
    const SESSION_BUILD_TMDB_LINK_TOTAL = 'session_build_tmdb_link_total';

    /**
     * @return array
     */
    public static function getAll(): array
    {
        return [
            self::SESSION_BUILD_TMDB_LINK_PROGRESS,
            self::SESSION_BUILD_TMDB_LINK_TOTAL,
        ];
    }
}
