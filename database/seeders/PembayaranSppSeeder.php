<?php

namespace Database\Seeders;

use App\Models\PembayaranSpp;
use App\Models\Siswa;
use App\Models\TagihanSpp;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PembayaranSppSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil user dengan role bendahara / admin untuk dijadikan petugas
        $petugas = User::first();
        if (!$petugas) {
            $this->command->warn('PembayaranSppSeeder: Tidak ada user ditemukan. Lewati seeder ini.');
            return;
        }

        // Ambil semua siswa (batasi 30 untuk demo)
        $siswas = Siswa::with('kelas')->take(30)->get();
        $tahun  = 2025;
        $totalTrx = 0;

        $metode = ['tunai', 'tunai', 'tunai', 'transfer', 'qris'];

        foreach ($siswas as $siswa) {
            // Ambil 2–4 tagihan belum bayar milik siswa ini
            $tagihan = TagihanSpp::where('siswa_id', $siswa->id)
                ->where('status', 'belum_bayar')
                ->orderBy('tahun')
                ->orderBy('bulan')
                ->take(rand(2, 4))
                ->get();

            if ($tagihan->isEmpty()) continue;

            // Generate no kwitansi
            $noKwitansi = 'SPP-' . $tahun . '-' . str_pad($totalTrx + 1, 5, '0', STR_PAD_LEFT);
            $totalBayar = $tagihan->sum('nominal');
            $tglBayar   = now()->subDays(rand(1, 60))->format('Y-m-d');
            $metodeBayar = $metode[array_rand($metode)];

            DB::beginTransaction();
            try {
                // Insert pembayaran
                $pembayaranId = DB::table('pembayaran_spp')->insertGetId([
                    'no_kwitansi'  => $noKwitansi,
                    'siswa_id'     => $siswa->id,
                    'user_id'      => $petugas->id,
                    'total_bayar'  => $totalBayar,
                    'tanggal_bayar' => $tglBayar,
                    'metode_bayar' => $metodeBayar,
                    'catatan'      => null,
                    'created_at'   => now(),
                    'updated_at'   => now(),
                ]);

                // Insert detail & update status tagihan
                foreach ($tagihan as $t) {
                    DB::table('detail_pembayaran')->insert([
                        'pembayaran_spp_id' => $pembayaranId,
                        'tagihan_spp_id'    => $t->id,
                        'nominal_bayar'     => $t->nominal,
                        'created_at'        => now(),
                        'updated_at'        => now(),
                    ]);

                    DB::table('tagihan_spp')->where('id', $t->id)->update(['status' => 'lunas']);
                }

                DB::commit();
                $totalTrx++;
            } catch (\Exception $e) {
                DB::rollBack();
                $this->command->error("Gagal seed pembayaran siswa {$siswa->nama_lengkap}: {$e->getMessage()}");
            }
        }

        $this->command->info("PembayaranSppSeeder: {$totalTrx} transaksi pembayaran di-seed.");
    }
}
