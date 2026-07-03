<?php

namespace Database\Seeders;

use App\Models\Siswa;
use App\Models\TarifSpp;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TagihanSppSeeder extends Seeder
{
    public function run(): void
    {
        $tahunAjaran = '2025/2026';
        $startYear   = 2025;
        $endYear     = 2026;

        $months = [
            ['bulan' => 7, 'tahun' => $startYear],
            ['bulan' => 8, 'tahun' => $startYear],
            ['bulan' => 9, 'tahun' => $startYear],
            ['bulan' => 10, 'tahun' => $startYear],
            ['bulan' => 11, 'tahun' => $startYear],
            ['bulan' => 12, 'tahun' => $startYear],
            ['bulan' => 1, 'tahun' => $endYear],
            ['bulan' => 2, 'tahun' => $endYear],
            ['bulan' => 3, 'tahun' => $endYear],
            ['bulan' => 4, 'tahun' => $endYear],
            ['bulan' => 5, 'tahun' => $endYear],
            ['bulan' => 6, 'tahun' => $endYear],
        ];

        $siswas = Siswa::with('kelas')->get();
        $total  = 0;

        foreach ($siswas as $siswa) {
            if (!$siswa->kelas) continue;

            $tarif = TarifSpp::where('tingkat', $siswa->kelas->tingkat)
                ->where('tahun_ajaran', $tahunAjaran)
                ->first();

            if (!$tarif) continue;

            $rows = [];
            foreach ($months as $m) {
                $bulan = $m['bulan'];
                $tahunBulan = $m['tahun'];

                $existing = DB::table('tagihan_spp')
                    ->where('siswa_id', $siswa->id)
                    ->where('bulan', $bulan)
                    ->where('tahun', $tahunBulan)
                    ->exists();

                if ($existing) continue;

                $rows[] = [
                    'siswa_id'     => $siswa->id,
                    'tarif_spp_id' => $tarif->id,
                    'bulan'        => $bulan,
                    'tahun'        => $tahunBulan,
                    'nominal'      => $tarif->nominal,
                    'status'       => 'belum_bayar',
                    'created_at'   => now(),
                    'updated_at'   => now(),
                ];
            }

            if (!empty($rows)) {
                DB::table('tagihan_spp')->insert($rows);
                $total += count($rows);
            }
        }

        $this->command->info("TagihanSppSeeder: {$total} tagihan di-generate untuk {$siswas->count()} siswa (TA {$tahunAjaran}).");
    }
}
