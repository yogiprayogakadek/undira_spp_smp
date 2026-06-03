<?php

namespace App\Http\Controllers\Main;

use App\Http\Controllers\Controller;
use App\Services\KelasService;
use App\Services\SiswaService;
use App\Services\TagihanSppService;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class TagihanSppController extends Controller
{
    public function __construct(
        protected TagihanSppService $tagihanSppService,
        protected SiswaService      $siswaService,
        protected KelasService      $kelasService,
    ) {}

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $tagihan = $this->tagihanSppService->getAll(['*'], $request->query('siswa_id'), $request->query('kelas_id'), $request->query('tingkat'));

            return DataTables::of($tagihan)
                ->addIndexColumn()
                ->addColumn('siswa_nama', fn ($row) => $row->siswa?->nama_lengkap ?? '—')
                ->addColumn('kelas_nama', fn ($row) => $row->siswa?->kelas?->nama ?? '—')
                ->addColumn('bulan_label', fn ($row) => \App\Models\TagihanSpp::namaBulan($row->bulan) . ' ' . $row->tahun)
                ->addColumn('nominal_fmt', fn ($row) => 'Rp ' . number_format($row->nominal, 0, ',', '.'))
                ->addColumn('status_badge', function ($row) {
                    $map = [
                        'belum_bayar' => ['danger',  'Belum Bayar'],
                        'sebagian'    => ['warning', 'Sebagian'],
                        'lunas'       => ['success', 'Lunas'],
                    ];
                    [$color, $label] = $map[$row->status] ?? ['secondary', $row->status];
                    return "<span class='badge bg-{$color}-subtle text-{$color} border border-{$color}-subtle px-2 fw-semibold'>{$label}</span>";
                })
                ->addColumn('actions', function ($row) {
                    if (auth()->user()->role === 'kepala sekolah') {
                        return '—';
                    }
                    if ($row->status !== 'belum_bayar') {
                        return '<button class="btn btn-secondary btn-sm px-2 py-1 border-0" style="opacity:0.5; cursor:not-allowed;" title="Hanya tagihan belum bayar yang dapat diedit" disabled><iconify-icon icon="solar:pen-bold" class="align-middle"></iconify-icon></button>';
                    }
                    return '<button class="btn btn-warning btn-sm btn-edit text-white px-2 py-1 border-0 shadow-sm" data-id="'.$row->id.'" data-nominal="'.(float)$row->nominal.'" data-siswa="'.e($row->siswa?->nama_lengkap).'" data-periode="'.e(\App\Models\TagihanSpp::namaBulan($row->bulan) . ' ' . $row->tahun).'"><iconify-icon icon="solar:pen-bold" class="align-middle"></iconify-icon></button>';
                })
                ->rawColumns(['status_badge', 'actions'])
                ->make(true);
        }

        $filteredSiswa = $request->query('siswa_id') ? \App\Models\Siswa::find($request->query('siswa_id')) : null;
        $kelasList = $this->kelasService->getAll(['id', 'nama', 'tingkat'])->sortBy([
            ['tingkat', 'asc'],
            ['nama', 'asc'],
        ]);

        return view('main.tagihan_spp.index', compact('filteredSiswa', 'kelasList'));
    }

    public function getKelas(Request $request)
    {
        $tingkat = $request->tingkat;
        if (!$tingkat) return response()->json([]);
        
        $kelas = $this->kelasService->getByTingkat((int)$tingkat);
        return response()->json($kelas);
    }

    public function getSiswa(Request $request)
    {
        $kelasId = $request->kelas_id;
        if (!$kelasId) return response()->json([]);

        $siswa = $this->siswaService->getByKelas((int)$kelasId);
        return response()->json($siswa);
    }

    public function generate(Request $request)
    {
        if (auth()->user()->role === 'kepala sekolah') {
            abort(403, 'Akses ditolak.');
        }
        $request->validate([
            'siswa_id'     => 'required|integer|exists:siswa,id',
            'tahun_ajaran' => 'required|regex:/^\d{4}\/\d{4}$/',
        ], [
            'siswa_id.required'     => 'Siswa wajib dipilih.',
            'tahun_ajaran.required' => 'Tahun ajaran wajib diisi.',
            'tahun_ajaran.regex'    => 'Format tahun ajaran: 2025/2026.',
        ]);

        $siswa = $this->siswaService->findById(['id', 'kelas_id', 'nama_lengkap'], $request->siswa_id);
        $tahun = (int) explode('/', $request->tahun_ajaran)[0];

        try {
            $generated = $this->tagihanSppService->generateTahunan(
                $siswa->id,
                $siswa->kelas->tingkat,
                $tahun,
                $request->tahun_ajaran
            );

            $count = count($generated);
            return redirect()->route('tagihan-spp.index', ['siswa_id' => $siswa->id])
                ->with('success', "{$count} tagihan SPP berhasil di-generate untuk {$siswa->nama_lengkap}.");
        } catch (\RuntimeException $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        if (auth()->user()->role === 'kepala sekolah') {
            abort(403, 'Akses ditolak.');
        }

        $request->validate([
            'nominal' => 'required|numeric|min:0',
        ], [
            'nominal.required' => 'Nominal wajib diisi.',
            'nominal.numeric'  => 'Nominal harus berupa angka.',
            'nominal.min'      => 'Nominal tidak boleh kurang dari 0.',
        ]);

        $tagihan = $this->tagihanSppService->findById(['id', 'status'], $id);

        if ($tagihan->status !== 'belum_bayar') {
            return redirect()->back()->with('error', 'Tagihan yang sudah dibayar tidak dapat diubah.');
        }

        $this->tagihanSppService->update($id, [
            'nominal' => $request->nominal,
        ]);

        return redirect()->route('tagihan-spp.index')
            ->with('success', 'Nominal tagihan SPP berhasil diperbarui.');
    }
}
