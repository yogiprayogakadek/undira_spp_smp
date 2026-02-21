<?php

namespace App\Http\Controllers\Main;

use App\Http\Controllers\Controller;
use App\Http\Requests\PembayaranSppStoreRequest;
use App\Services\PembayaranSppService;
use App\Services\SiswaService;
use App\Services\TagihanSppService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;

class PembayaranSppController extends Controller
{
    public function __construct(
        protected PembayaranSppService $pembayaranSppService,
        protected TagihanSppService    $tagihanSppService,
        protected SiswaService         $siswaService,
    ) {}

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $pembayaran = $this->pembayaranSppService->getAll(['*']);

            return DataTables::of($pembayaran)
                ->addIndexColumn()
                ->addColumn('siswa_nama', fn ($row) => $row->siswa?->nama_lengkap ?? '—')
                ->addColumn('kelas_nama', fn ($row) => $row->siswa?->kelas?->nama ?? '—')
                ->addColumn('total_fmt', fn ($row) => 'Rp ' . number_format($row->total_bayar, 0, ',', '.'))
                ->addColumn('tanggal', fn ($row) => $row->tanggal_bayar?->format('d/m/Y') ?? '—')
                ->addColumn('metode_badge', function ($row) {
                    $map = [
                        'tunai'    => ['success', 'Tunai'],
                        'transfer' => ['info',    'Transfer'],
                        'qris'     => ['primary',  'QRIS'],
                    ];
                    [$color, $label] = $map[$row->metode_bayar] ?? ['secondary', $row->metode_bayar];
                    return "<span class='badge bg-{$color}-subtle text-{$color} border border-{$color}-subtle px-2 fw-semibold'>{$label}</span>";
                })
                ->addColumn('actions', function ($row) {
                    $btnDetail = '
                    <a href="' . route('pembayaran-spp.show', $row->id) . '">
                        <button type="button" class="justify-content-center w-80 btn mb-1 bg-primary-subtle text-primary">
                            <i class="ti ti-eye fs-4 me-2"></i> Detail
                        </button>
                    </a>';

                    if (auth()->user()->role === 'kepala sekolah') {
                        return $btnDetail;
                    }

                    return $btnDetail . '
                    <form action="' . route('pembayaran-spp.delete', $row->id) . '" method="POST" style="display:inline"
                        onsubmit="return confirm(\'Batalkan pembayaran ini? Status tagihan akan dikembalikan.\')">
                        ' . csrf_field() . method_field('DELETE') . '
                        <button type="submit" class="justify-content-center w-80 btn mb-1 bg-danger-subtle text-danger">
                            <i class="ti ti-trash fs-4 me-2"></i> Batal
                        </button>
                    </form>
                    ';
                })
                ->rawColumns(['actions', 'metode_badge'])
                ->make(true);
        }

        $siswa = $this->siswaService->getAll(['id', 'nama_lengkap', 'kelas_id']);
        return view('main.pembayaran_spp.index', compact('siswa'));
    }

    public function create(Request $request)
    {
        $siswa   = $this->siswaService->getAll(['id', 'nama_lengkap', 'kelas_id']);
        $tagihan = collect();

        if ($request->filled('siswa_id')) {
            $tagihan = $this->tagihanSppService->getTagihanBelumLunas((int) $request->siswa_id);
        }

        return view('main.pembayaran_spp.create', compact('siswa', 'tagihan'));
    }

    public function store(PembayaranSppStoreRequest $request)
    {
        if (auth()->user()->role === 'kepala sekolah') {
            abort(403, 'Akses ditolak.');
        }
        $data      = $request->only(['siswa_id', 'tanggal_bayar', 'metode_bayar', 'catatan']);
        $data['user_id'] = Auth::id();
        $tagihanIds = $request->input('tagihan_ids', []);

        try {
            $this->pembayaranSppService->bayar($data, $tagihanIds);
            return redirect()->route('pembayaran-spp.index')
                ->with('success', 'Pembayaran SPP berhasil dicatat.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menyimpan pembayaran: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $pembayaran = $this->pembayaranSppService->findById(['*'], $id);
        return view('main.pembayaran_spp.show', compact('pembayaran'));
    }

    public function delete($id)
    {
        if (auth()->user()->role === 'kepala sekolah') {
            abort(403, 'Akses ditolak.');
        }
        try {
            $this->pembayaranSppService->delete($id);
            return redirect()->route('pembayaran-spp.index')
                ->with('success', 'Pembayaran berhasil dibatalkan dan tagihan dikembalikan.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal membatalkan: ' . $e->getMessage());
        }
    }

    /**
     * API: return tagihan belum lunas milik siswa (untuk AJAX di form pembayaran).
     */
    public function getTagihanSiswa(Request $request)
    {
        $request->validate(['siswa_id' => 'required|integer|exists:siswa,id']);
        $tagihan = $this->tagihanSppService->getTagihanBelumLunas($request->siswa_id);
        return response()->json($tagihan->map(fn ($t) => [
            'id'      => $t->id,
            'label'   => \App\Models\TagihanSpp::namaBulan($t->bulan) . ' ' . $t->tahun,
            'nominal' => $t->nominal,
        ]));
    }
}
