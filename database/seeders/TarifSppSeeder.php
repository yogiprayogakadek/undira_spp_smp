<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TarifSppSeeder extends Seeder
{
    public function run(): void
    {
        $tahunAjaran = ['2024/2025', '2025/2026'];

        $tarif = [];
        foreach ($tahunAjaran as $ta) {
            $tarif[] = [
                'tingkat'      => 7,
                'tahun_ajaran' => $ta,
                'nominal'      => 150000,
                'keterangan'   => 'Tarif SPP Kelas VII tahun ajaran ' . $ta,
                'created_at'   => now(),
                'updated_at'   => now(),
            ];
            $tarif[] = [
                'tingkat'      => 8,
                'tahun_ajaran' => $ta,
                'nominal'      => 150000,
                'keterangan'   => 'Tarif SPP Kelas VIII tahun ajaran ' . $ta,
                'created_at'   => now(),
                'updated_at'   => now(),
            ];
            $tarif[] = [
                'tingkat'      => 9,
                'tahun_ajaran' => $ta,
                'nominal'      => 175000,
                'keterangan'   => 'Tarif SPP Kelas IX tahun ajaran ' . $ta,
                'created_at'   => now(),
                'updated_at'   => now(),
            ];
        }

        DB::table('tarif_spp')->insert($tarif);

        $this->command->info('TarifSppSeeder: ' . count($tarif) . ' tarif di-seed (' . implode(', ', $tahunAjaran) . ').');
    }
}
