<?php

namespace App\Services;

use App\Enums\RoleEnum;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserService
{
    public static function createUser(array $data): User
    {
        $user = User::create([
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'] ?? null,
            'password' => Hash::make($data['password']),
            'fcm_token' => $data['fcm_token'] ?? null,
            'phone_number' => $data['phone_number'],
            'photo' => $data['photo'] != null ? NewPublicPhoto($data['photo'], 'profiles') : null,
        ]);

        $user->assignRole('user');

        return $user;
    }

    public static function updatePhoneNumber(User $user, $newPhoneNumber): void
    {
        $user->update(['phone_number' => $newPhoneNumber]);
    }

    public static function updatePassword($user, $newPassword): void
    {
        $user->update([
            'password' => Hash::make($newPassword),
        ]);
    }

    public function FormatRoles(User $user): User
    {
        $user->load('roles:name');
        $roles = RoleEnum::getAllRoles();
        $roleStatuses = [];
        $userRoles = $user->roles->pluck('name')->toArray();
        foreach ($roles as $role) {
            $roleStatuses[$role] = in_array($role, $userRoles);
        }
        $user->roles_status = $roleStatuses;
        unset($user->roles);

        return $user;
    }
}
