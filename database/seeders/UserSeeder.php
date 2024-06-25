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
        $password = 'Team2@123';
        $createMultipleData = [
            ['name' => 'Khanh', 'email' => 'khanh@gmail.com', 'password' => Hash::make($password), 'role' => 'superAdmin'],
            ['name' => 'Tin', 'email' => 'tin@gmail.com', 'password' => Hash::make($password), 'role' => 'superAdmin'],
            ['name' => 'Hieu', 'email' => 'hieu@gmail.com', 'password' => Hash::make($password), 'role' => 'superAdmin'],
            ['name' => 'client1', 'email' => 'client1@gmail.com', 'password' => Hash::make($password), 'role' => 'admin'],
            ['name' => 'client2', 'email' => 'client2@gmail.com', 'password' => Hash::make($password), 'role' => 'admin'],
            ['name' => 'client3', 'email' => 'client3@gmail.com', 'password' => Hash::make($password), 'role' => 'admin'],
        ];

        User::insert($createMultipleData);
    }
}
