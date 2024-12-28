<?php

namespace App\Enums;

enum RoleEnum
{
    const Admin = 'admin';

    const User = 'user';

    const Manager = 'manager';

    public static function getAllRoles(): array
    {
        return [
            self::Manager,
            self::Admin,
            self::User,
        ];
    }
}
