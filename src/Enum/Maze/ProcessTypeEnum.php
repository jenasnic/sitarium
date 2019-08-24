<?php

namespace App\Enum\Maze;

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
            self::FILMOGRAPHY,
            self::CASTING,
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
