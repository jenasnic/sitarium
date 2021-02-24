<?php

namespace App\Enum\Tmdb;

class ProcessStatusEnum
{
    public const PENDING = 'pending';
    public const SUCCESS = 'success';
    public const ERROR = 'error';

    public static function getAll(): array
    {
        return [
            self::PENDING,
            self::SUCCESS,
            self::ERROR,
        ];
    }

    public static function exists(string $status): bool
    {
        return in_array($status, self::getAll());
    }
}
