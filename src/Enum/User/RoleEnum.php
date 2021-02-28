<?php

namespace App\Enum\User;

class RoleEnum
{
    public const ROLE_ADMIN = 'ROLE_ADMIN';
    public const ROLE_USER = 'ROLE_USER';

    /**
     * @return array<string>
     */
    public static function getAll(): array
    {
        return [
            self::ROLE_ADMIN,
            self::ROLE_USER,
        ];
    }
}
