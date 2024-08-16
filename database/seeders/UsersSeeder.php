<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'id' => (string) \Illuminate\Support\Str::uuid(),
            'name' => 'Admin One',
            'email' => 'admin1@example.com',
            'password' => Hash::make('admin1'),
            'role' => 'admin',
        ]);

        User::create([
            'id' => (string) \Illuminate\Support\Str::uuid(),
            'name' => 'Admin Two',
            'email' => 'admin2@example.com',
            'password' => Hash::make('admin2'),
            'role' => 'admin',
        ]);

        // Create Regular users
        User::create([
            'id' => (string) \Illuminate\Support\Str::uuid(),
            'name' => 'User One',
            'email' => 'user1@example.com',
            'password' => Hash::make('user1'),
            'role' => 'user',
        ]);

        User::create([
            'id' => (string) \Illuminate\Support\Str::uuid(),
            'name' => 'User Two',
            'email' => 'user2@example.com',
            'password' => Hash::make('user2'),
            'role' => 'user',
        ]);
    }
}
