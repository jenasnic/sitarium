<?php

namespace App\Enum\User;

class Roles
{
    const ROLE_ADMIN = 'ROLE_ADMIN';
    const ROLE_USER = 'ROLE_USER';

    /**
     * @return array
     */
    public static function getAll(): array
    {
        return [
            self::ROLE_ADMIN,
            self::ROLE_USER,
        ];
    }
}
