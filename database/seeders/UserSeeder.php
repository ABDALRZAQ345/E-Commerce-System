<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory(10)->create();
        User::create([
            'first_name' => 'admin',
            'phone_number' => '0912345678',
            'password' => Hash::make('admin12A*'),
        ])->assignRole('admin');

    }
}
