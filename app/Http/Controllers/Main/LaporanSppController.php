<?php

namespace App\Http\Controllers\Main;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\TagihanSpp;
use App\Models\TarifSpp;
use Illuminate\Http\Request;

class LaporanSppController extends Controller
{
    public function index()
    {
        $tahunAjaran = TarifSpp::distinct()->pluck('tahun_ajaran');
        $kelas = Kelas::orderBy('tingkat')->orderBy('nama')->get();
        return view('main.laporan_spp.index', compact('tahunAjaran', 'kelas'));
    }

    public function preview(Request $request)
    {
        $request->validate([
            'tahun_ajaran' => 'required',
            'tingkat'      => 'nullable',
            'kelas_id'     => 'nullable',
            'bulan'        => 'required|integer|between:1,12',
            'status'       => 'nullable|in:lunas,sebagian,belum_bayar',
        ]);

        $query = TagihanSpp::with(['siswa.kelas', 'tarif'])
            ->whereHas('tarif', function ($q) use ($request) {
                $q->where('tahun_ajaran', $request->tahun_ajaran);
            })
            ->where('bulan', $request->bulan);

        if ($request->tingkat) {
            $query->whereHas('siswa.kelas', function ($q) use ($request) {
                $q->where('tingkat', $request->tingkat);
            });
        }

        if ($request->kelas_id) {
            $query->whereHas('siswa', function ($q) use ($request) {
                $q->where('kelas_id', $request->kelas_id);
            });
        }

        if ($request->status) {
            $query->where('status', $request->status);
        }

        $data = $query->get();
        $bulanLabel = TagihanSpp::namaBulan($request->bulan);

        return view('main.laporan_spp.index', [
            'tahunAjaran' => TarifSpp::distinct()->pluck('tahun_ajaran'),
            'kelas'       => Kelas::orderBy('tingkat')->orderBy('nama')->get(),
            'results'     => $data,
            'filters'     => $request->all(),
            'bulanLabel'  => $bulanLabel
        ]);
    }

    public function print(Request $request)
    {
        $query = TagihanSpp::with(['siswa.kelas', 'tarif'])
            ->whereHas('tarif', function ($q) use ($request) {
                $q->where('tahun_ajaran', $request->tahun_ajaran);
            })
            ->where('bulan', $request->bulan);

        if ($request->tingkat) {
            $query->whereHas('siswa.kelas', function ($q) use ($request) {
                $q->where('tingkat', $request->tingkat);
            });
        }

        if ($request->kelas_id) {
            $query->whereHas('siswa', function ($q) use ($request) {
                $q->where('kelas_id', $request->kelas_id);
            });
        }

        if ($request->status) {
            $query->where('status', $request->status);
        }

        $data = $query->get();
        $bulanLabel = TagihanSpp::namaBulan($request->bulan);
        $kelasInfo = $request->kelas_id ? Kelas::find($request->kelas_id)->nama : ($request->tingkat ? "Kelas Tkt " . $request->tingkat : "Semua Kelas");

        return view('main.laporan_spp.print', [
            'results'    => $data,
            'filters'    => $request->all(),
            'bulanLabel' => $bulanLabel,
            'kelasInfo'  => $kelasInfo
        ]);
    }
}
