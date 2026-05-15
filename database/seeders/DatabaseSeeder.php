<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create the official administrator account
        User::create([
            'name' => 'System Director',
            'email' => 'director@lspu.edu.ph',
            'password' => Hash::make('adminlspu'), // Secure default password
        ]);
    }
}