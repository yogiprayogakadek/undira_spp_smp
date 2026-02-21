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
        $tahun       = 2025;

        $siswas = Siswa::with('kelas')->get();
        $total  = 0;

        foreach ($siswas as $siswa) {
            if (!$siswa->kelas) continue;

            $tarif = TarifSpp::where('tingkat', $siswa->kelas->tingkat)
                ->where('tahun_ajaran', $tahunAjaran)
                ->first();

            if (!$tarif) continue;

            $rows = [];
            for ($bulan = 1; $bulan <= 12; $bulan++) {
                // Siswa baru (kelas 7) mulai bayar bulan Juli (awal tahun ajaran)
                $bulanMulai = $siswa->kelas->tingkat === 7 ? 7 : 1;
                if ($bulan < $bulanMulai) continue;

                $existing = DB::table('tagihan_spp')
                    ->where('siswa_id', $siswa->id)
                    ->where('bulan', $bulan)
                    ->where('tahun', $tahun)
                    ->exists();

                if ($existing) continue;

                $rows[] = [
                    'siswa_id'     => $siswa->id,
                    'tarif_spp_id' => $tarif->id,
                    'bulan'        => $bulan,
                    'tahun'        => $tahun,
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
