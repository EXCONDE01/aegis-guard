<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Campus Director (Monitoring Tools Only)
        User::create([
            'name' => 'Campus Director',
            'email' => 'director@lspu.edu.ph',
            'password' => Hash::make('adminlspu'),
            'role' => 'director',
        ]);

        // 2. IT System Admin (Hardware Management Access)
        User::create([
            'name' => 'System Administrator',
            'email' => 'admin@lspu.edu.ph',
            'password' => Hash::make('AegisGuard2026!'),
            'role' => 'admin',
        ]);
    }
}