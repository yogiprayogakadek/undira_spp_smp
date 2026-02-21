<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            KelasSeeder::class,
            UserSeeder::class,
            SiswaSeeder::class,
            TarifSppSeeder::class,
            TagihanSppSeeder::class,
            PembayaranSppSeeder::class,
        ]);
    }
}
