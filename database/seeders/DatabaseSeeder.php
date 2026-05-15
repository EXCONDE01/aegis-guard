<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Threshold;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Campus Director
        User::create([
            'name' => 'Campus Director',
            'email' => 'director@lspu.edu.ph',
            'password' => Hash::make('adminlspu'),
            'role' => 'director',
        ]);

        // 2. IT System Admin
        User::create([
            'name' => 'System Administrator',
            'email' => 'admin@lspu.edu.ph',
            'password' => Hash::make('AegisGuard2026!'),
            'role' => 'admin',
        ]);

        // 3. Initialize Default Thresholds
        Threshold::create([
            'temp_warning' => 35.0,
            'temp_critical' => 45.0,
            'smoke_warning' => 10.0,
            'smoke_critical' => 15.0,
        ]);
    }
}