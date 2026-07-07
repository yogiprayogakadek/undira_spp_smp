<?php

namespace App\Http\Controllers\Main;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\Siswa;
use App\Services\TagihanSppService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KenaikanKelasController extends Controller
{
    public function __construct(
        protected TagihanSppService $tagihanSppService
    ) {}

    public function index()
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Akses ditolak. Hanya Admin yang dapat mengakses halaman ini.');
        }

        $kelasList = Kelas::orderBy('tingkat')->orderBy('nama')->get();
        
        // Dapatkan semua tahun ajaran dari tarif SPP untuk pilihan dropdown
        $tahunAjaranList = DB::table('tarif_spp')
            ->distinct()
            ->orderBy('tahun_ajaran', 'desc')
            ->pluck('tahun_ajaran')
            ->toArray();

        // Prediksi tahun ajaran berikutnya untuk memudahkan pengisian
        $latestTa = DB::table('tarif_spp')->max('tahun_ajaran');
        $nextTa = '';
        if ($latestTa && preg_match('/^(\d{4})\/(\d{4})$/', $latestTa, $matches)) {
            $nextTa = ((int)$matches[1] + 1) . '/' . ((int)$matches[2] + 1);
        } else {
            $currentYear = (int)date('Y');
            $nextTa = $currentYear . '/' . ($currentYear + 1);
        }

        // Pastikan nextTa masuk ke daftar jika belum ada
        if ($nextTa && !in_array($nextTa, $tahunAjaranList)) {
            array_unshift($tahunAjaranList, $nextTa);
        }

        return view('main.kenaikan_kelas.index', compact('kelasList', 'tahunAjaranList', 'nextTa'));
    }

    public function getSiswaByKelas(Request $request)
    {
        $kelasId = $request->kelas_id;
        if (!$kelasId) {
            return response()->json([]);
        }

        $siswa = Siswa::where('kelas_id', $kelasId)
            ->where('status', 'aktif')
            ->orderBy('nama_lengkap')
            ->get(['id', 'nama_lengkap', 'nis', 'jenis_kelamin']);

        return response()->json($siswa);
    }

    public function proses(Request $request)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Akses ditolak.');
        }

        $request->validate([
            'kelas_asal_id' => 'required|exists:kelas,id',
            'tipe_proses'   => 'required|in:naik,lulus',
            'siswa_ids'     => 'required|array|min:1',
            'siswa_ids.*'   => 'required|exists:siswa,id',
            'kelas_tujuan_id'   => 'required_if:tipe_proses,naik|nullable|exists:kelas,id',
            'tahun_ajaran_baru' => 'required_if:tipe_proses,naik|nullable|regex:/^\d{4}\/\d{4}$/',
        ], [
            'kelas_asal_id.required' => 'Kelas asal wajib dipilih.',
            'tipe_proses.required'   => 'Tipe proses wajib dipilih.',
            'siswa_ids.required'     => 'Pilih minimal satu siswa untuk diproses.',
            'kelas_tujuan_id.required_if' => 'Kelas tujuan wajib dipilih untuk proses kenaikan kelas.',
            'tahun_ajaran_baru.required_if' => 'Tahun ajaran baru wajib dipilih/diisi untuk proses kenaikan kelas.',
            'tahun_ajaran_baru.regex' => 'Format tahun ajaran baru harus YYYY/YYYY (contoh: 2026/2027).',
        ]);

        $siswaIds = $request->siswa_ids;
        $tipeProses = $request->tipe_proses;

        try {
            DB::beginTransaction();

            if ($tipeProses === 'lulus') {
                // Proses kelulusan
                Siswa::whereIn('id', $siswaIds)->update([
                    'kelas_id' => null,
                    'status'   => 'lulus'
                ]);

                $message = count($siswaIds) . ' siswa berhasil diluluskan.';
            } else {
                // Proses kenaikan kelas
                $kelasTujuan = Kelas::findOrFail($request->kelas_tujuan_id);
                $tingkatTujuan = (int)$kelasTujuan->tingkat;
                $tahunAjaranBaru = $request->tahun_ajaran_baru;
                $tahun = (int) explode('/', $tahunAjaranBaru)[0];

                // Update kelas siswa
                Siswa::whereIn('id', $siswaIds)->update([
                    'kelas_id' => $kelasTujuan->id,
                    'status'   => 'aktif'
                ]);

                // Generate tagihan SPP baru untuk tahun ajaran baru
                foreach ($siswaIds as $siswaId) {
                    $this->tagihanSppService->generateTahunan(
                        $siswaId,
                        $kelasTujuan->id,
                        $tingkatTujuan,
                        $tahun,
                        $tahunAjaranBaru
                    );
                }

                $message = count($siswaIds) . ' siswa berhasil dinaikkan ke kelas ' . $kelasTujuan->nama . ' dan tagihan SPP untuk T.A ' . $tahunAjaranBaru . ' telah dibuat.';
            }

            DB::commit();
            return redirect()->route('kenaikan-kelas.index')->with('success', $message);

        } catch (\RuntimeException $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->with('error', $e->getMessage());
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage());
        }
    }
}
