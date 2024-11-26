<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

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
            'password' => 'admin12A*'
        ])->assignRole('admin');

    }
}
