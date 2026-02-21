<?php

namespace App\Http\Controllers\Main;

use App\Http\Controllers\Controller;
use App\Services\SiswaService;
use App\Services\TagihanSppService;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class TagihanSppController extends Controller
{
    public function __construct(
        protected TagihanSppService $tagihanSppService,
        protected SiswaService      $siswaService,
    ) {}

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $tagihan = $this->tagihanSppService->getAll(['*']);

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
                ->rawColumns(['status_badge'])
                ->make(true);
        }

        $siswa = $this->siswaService->getAll(['id', 'nama_lengkap', 'kelas_id']);
        return view('main.tagihan_spp.index', compact('siswa'));
    }

    public function generate(Request $request)
    {
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
            return redirect()->route('tagihan-spp.index')
                ->with('success', "{$count} tagihan SPP berhasil di-generate untuk {$siswa->nama_lengkap}.");
        } catch (\RuntimeException $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
