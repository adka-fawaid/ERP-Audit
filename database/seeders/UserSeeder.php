<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        $users = [
            [
                'nik' => '044MG',
                'nama' => 'Administrator',
                'email' => 'admin@qad.local',
                'jenis_kelamin' => 0,
                'status_hapus' => 1,
                'freeze' => 0,
                'password_hash' => Hash::make('Admin123!'),
            ],
            [
                'nik' => '045MG',
                'nama' => 'Administrator 2',
                'email' => 'admin2@qad.local',
                'jenis_kelamin' => 0,
                'status_hapus' => 1,
                'freeze' => 0,
                'password_hash' => Hash::make('Admin123!'),
            ],
        ];

        foreach ($users as $user) {
            DB::table('mst_anggota')->updateOrInsert(
                ['nik' => $user['nik']],
                $user
            );

            DB::table('qad_user_roles')->updateOrInsert(
                ['nik' => $user['nik']],
                [
                    'role' => 'admin',
                    'status' => 'active',
                    'updated_at' => now(),
                    'created_at' => now(),
                ]
            );
        }
    }
}   