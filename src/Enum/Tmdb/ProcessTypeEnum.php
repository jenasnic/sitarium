<?php

namespace App\Enum\Tmdb;

class ProcessTypeEnum
{
    const FILMOGRAPHY = 'filmography';
    const CASTING = 'casting';

    /**
     * @return array
     */
    public static function getAll(): array
    {
        return [
            self::CASTING,
            self::FILMOGRAPHY,
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
