<?php

namespace App\Enum\Tmdb;

class ProcessTypeEnum
{
    public const FILMOGRAPHY = 'filmography';
    public const CASTING = 'casting';
    public const QUIZ_LINK = 'quiz_link';
    public const SYNCHRONIZATION = 'synchronization';

    public static function getAll(): array
    {
        return [
            self::CASTING,
            self::FILMOGRAPHY,
            self::QUIZ_LINK,
            self::SYNCHRONIZATION,
        ];
    }

    public static function exists(string $status): bool
    {
        return in_array($status, self::getAll());
    }
}
