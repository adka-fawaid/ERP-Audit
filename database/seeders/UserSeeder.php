<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Internal Admin
        User::create([
            'auth_type' => 'company',
            'nik' => '100001',
            'name' => 'Admin QAD',
            'email' => null,
            'password' => null,
            'role' => 'admin',
            'is_active' => true,
        ]);

        // Internal Viewer
        User::create([
            'auth_type' => 'company',
            'nik' => '100002',
            'name' => 'Viewer Internal',
            'email' => null,
            'password' => null,
            'role' => 'viewer',
            'is_active' => true,
        ]);

        // External Auditor
        User::create([
            'auth_type' => 'external',
            'nik' => null,
            'name' => 'External Auditor',
            'email' => 'auditor@dummy.test',
            'password' => Hash::make('password123'),
            'role' => 'viewer',
            'is_active' => true,
        ]);
    }
}