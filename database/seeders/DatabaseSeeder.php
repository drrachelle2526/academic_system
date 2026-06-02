<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create or update a test user and a sample teacher user.
        User::updateOrCreate(
            ['email' => 'test@example.com'],
            ['name' => 'Test User', 'password' => bcrypt('password')]
        );

        User::updateOrCreate(
            ['email' => 'teacher@example.com'],
            ['name' => 'Sample Teacher', 'password' => bcrypt('password'), 'role' => 'teacher']
        );
    }
}
