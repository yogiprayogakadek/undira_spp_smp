<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KelasSeeder extends Seeder
{
    public function run(): void
    {
        $kelas = [
            ['nama' => 'VII A', 'tingkat' => 7],
            ['nama' => 'VII B', 'tingkat' => 7],
            ['nama' => 'VIII A', 'tingkat' => 8],
            ['nama' => 'VIII B', 'tingkat' => 8],
            ['nama' => 'IX A', 'tingkat' => 9],
            ['nama' => 'IX B', 'tingkat' => 9],
        ];

        DB::table('kelas')->insert(
            array_map(fn($k) => array_merge($k, [
                'created_at' => now(),
                'updated_at' => now(),
            ]), $kelas)
        );
    }
}
