<?php

namespace App\Tool;

class TmdbUtil
{
    /**
     * Allows to get minimum vote count value used in movies matching specified level.
     *
     * @param int $level Level we want to get matching min vote count. 0 for easy level, 1 for medium or 2 for hard level.
     *
     * @return int mininum vote count for movies matching specified level
     */
    public static function getMinVoteCountForLevel(int $level): int
    {
        // level : 0 [easy], 1 [medium], 2 [hard]
        switch ($level) {
            case 0:
                return 3500;
            case 1:
                return 2000;
        }

        return 0;
    }

    /**
     * Allows to get default base path URL for picture from TMDB.
     */
    public static function getBasePictureUrl(): string
    {
        return 'https://image.tmdb.org/t/p/w185/';
    }
}
