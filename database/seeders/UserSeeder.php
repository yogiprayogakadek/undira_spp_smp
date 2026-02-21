<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'email'    => 'admin@smpn1mauponggo.sch.id',
                'password' => Hash::make('password'),
                'role'     => 'admin',
                'is_active' => true,
                'profile'  => [
                    'nama_lengkap' => 'Administrator Sistem',
                    'alamat'       => 'Jl. Pendidikan No. 1, Mauponggo, Nagekeo',
                    'no_telp'      => '081234560001',
                    'nip'          => null,
                ],
            ],
            [
                'email'    => 'bendahara@smpn1mauponggo.sch.id',
                'password' => Hash::make('password'),
                'role'     => 'bendahara',
                'is_active' => true,
                'profile'  => [
                    'nama_lengkap' => 'Maria Goreti Dhae',
                    'alamat'       => 'Jl. Merdeka No. 5, Mauponggo, Nagekeo',
                    'no_telp'      => '081234560002',
                    'nip'          => '198504122010012020',
                ],
            ],
            [
                'email'    => 'kepsek@smpn1mauponggo.sch.id',
                'password' => Hash::make('password'),
                'role'     => 'kepala sekolah',
                'is_active' => true,
                'profile'  => [
                    'nama_lengkap' => 'Yohanes Pati Bera, S.Pd.',
                    'alamat'       => 'Jl. Diponegoro No. 10, Mauponggo, Nagekeo',
                    'no_telp'      => '081234560003',
                    'nip'          => '197203152000011005',
                ],
            ],
        ];

        foreach ($users as $data) {
            $profile = $data['profile'];
            unset($data['profile']);

            $userId = DB::table('users')->insertGetId(array_merge($data, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));

            DB::table('profiles')->insert(array_merge($profile, [
                'user_id'    => $userId,
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }
}
