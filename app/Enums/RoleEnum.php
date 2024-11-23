<?php

namespace App\Enums;

use App\Models\Permission;

enum RoleEnum
{
    const Manager = 'manager';

    const Admin = 'admin';

    const User = 'user';

    // Add other roles as needed

    public static function getAllRoles(): array
    {
        return [
            self::Manager,
            self::Admin,
            self::User,
            // Add other roles as needed
        ];
    }

}
