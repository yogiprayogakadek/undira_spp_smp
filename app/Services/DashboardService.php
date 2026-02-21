<?php

namespace App\Services;

use App\Repositories\SiswaRepository;
use App\Repositories\KelasRepository;
use App\Repositories\UserRepository;
use App\Repositories\PembayaranSppRepository;
use App\Repositories\TagihanSppRepository;
use Illuminate\Support\Facades\DB;

class DashboardService
{
    public function __construct(
        protected SiswaRepository         $siswaRepository,
        protected KelasRepository         $kelasRepository,
        protected UserRepository          $userRepository,
        protected PembayaranSppRepository $pembayaranSppRepository,
        protected TagihanSppRepository    $tagihanSppRepository,
    ) {}

    public function getAdminStats(): array
    {
        return [
            'total_siswa' => DB::table('siswa')->count(),
            'total_kelas' => DB::table('kelas')->count(),
            'total_user'  => DB::table('users')->count(),
            'total_tarif' => DB::table('tarif_spp')->count(),
            'total_pembayaran_all' => (float) DB::table('pembayaran_spp')->sum('total_bayar'),
        ];
    }

    public function getBendaharaStats(): array
    {
        $hariIni = now()->format('Y-m-d');
        
        return [
            'bayar_hari_ini_count' => DB::table('pembayaran_spp')->where('tanggal_bayar', $hariIni)->count(),
            'bayar_hari_ini_sum'   => (float) DB::table('pembayaran_spp')->where('tanggal_bayar', $hariIni)->sum('total_bayar'),
            'tagihan_belum_lunas'  => DB::table('tagihan_spp')->whereIn('status', ['belum_bayar', 'sebagian'])->count(),
            'tunggakan_nominal'    => (float) DB::table('tagihan_spp')->whereIn('status', ['belum_bayar', 'sebagian'])->sum('nominal'),
            'recent_transactions'  => $this->pembayaranSppRepository->getAll(['*'])->take(5),
        ];
    }

    public function getKepsekStats(): array
    {
        // Statistik siswa per kelas
        $siswaPerKelas = DB::table('kelas')
            ->leftJoin('siswa', 'kelas.id', '=', 'siswa.kelas_id')
            ->select('kelas.nama', DB::raw('count(siswa.id) as total'))
            ->groupBy('kelas.id', 'kelas.nama')
            ->get();

        // Pendapatan 6 bulan terakhir
        $pendapatanBulanan = DB::table('pembayaran_spp')
            ->select(DB::raw("DATE_FORMAT(tanggal_bayar, '%Y-%m') as bulan"), DB::raw('SUM(total_bayar) as total'))
            ->groupBy('bulan')
            ->orderBy('bulan', 'desc')
            ->limit(6)
            ->get();

        return [
            'total_siswa'       => DB::table('siswa')->count(),
            'total_pembayaran'  => (float) DB::table('pembayaran_spp')->sum('total_bayar'),
            'siswa_per_kelas'   => $siswaPerKelas,
            'pendapatan_bulan'   => $pendapatanBulanan,
        ];
    }

    public function getIncomeChartData(int $year, ?int $tingkat = null, ?int $kelasId = null): array
    {
        $query = DB::table('pembayaran_spp')
            ->join('siswa', 'pembayaran_spp.siswa_id', '=', 'siswa.id')
            ->join('kelas', 'siswa.kelas_id', '=', 'kelas.id')
            ->select(
                DB::raw('MONTH(pembayaran_spp.tanggal_bayar) as bulan'), 
                DB::raw('SUM(pembayaran_spp.total_bayar) as total')
            )
            ->whereYear('pembayaran_spp.tanggal_bayar', $year);

        if ($tingkat) {
            $query->where('kelas.tingkat', $tingkat);
        }

        if ($kelasId) {
            $query->where('kelas.id', $kelasId);
        }

        $data = $query->groupBy('bulan')
            ->pluck('total', 'bulan')
            ->toArray();

        // Ensure 12 months present
        $monthlyData = [];
        for ($i = 1; $i <= 12; $i++) {
            $monthlyData[] = (float) ($data[$i] ?? 0);
        }

        return $monthlyData;
    }
}
