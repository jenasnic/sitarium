<?php

namespace App\Enum\Tmdb;

class ProcessTypeEnum
{
    const FILMOGRAPHY = 'filmography';
    const CASTING = 'casting';
    const QUIZ_LINK = 'quiz_link';

    /**
     * @return array
     */
    public static function getAll(): array
    {
        return [
            self::CASTING,
            self::FILMOGRAPHY,
            self::QUIZ_LINK,
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
