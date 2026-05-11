<?php

namespace App\Http\Controllers\Main;

use App\Http\Controllers\Controller;
use App\Http\Requests\KelasStoreRequest;
use App\Http\Requests\KelasUpdateRequest;
use App\Services\KelasService;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class KelasController extends Controller
{
    public function __construct(protected KelasService $kelasService) {}

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $kelas = $this->kelasService->getAll(['id', 'nama', 'tingkat']);

            return DataTables::of($kelas)
                ->addIndexColumn()
                ->addColumn('nama', function ($row) {
                    $nama = $row->nama;
                    $angka = explode(' ', $nama);
                    $list = [
                        '7' => 'VII',
                        '8' => 'VIII',
                        '9' => 'IX'
                    ];

                    if (is_numeric($angka[0])) {
                        $nama = $list[$angka[0]] . ' ' . $angka[1] ?? $row->nama;
                    }

                    return $nama;
                })
                ->addColumn('tingkat', function ($row) {
                    return 'Kelas ' . $row->tingkat;
                })
                ->addColumn('actions', function ($row) {
                    if (auth()->user()->role === 'kepala sekolah') {
                        return '<span class="text-muted small">Read Only</span>';
                    }

                    // Split nama to get grade and rombel
                    $parts = explode(' ', $row->nama, 2);
                    $grade = $parts[0] ?? '';
                    $nama = $parts[1] ?? '';

                    return '
                    <button type="button"
                        class="btn-edit justify-content-center w-80 btn mb-1 bg-primary-subtle text-primary"
                        data-id="' . $row->id . '"
                        data-grade="' . $grade . '"
                        data-nama="' . $nama . '"
                        data-tingkat="' . $row->tingkat . '">
                        <i class="ti ti-pencil fs-4 me-2"></i>
                        Edit
                    </button>
                    ';
                })
                ->rawColumns(['actions', 'tingkat'])
                ->make(true);
        };

        return view('main.kelas.index');
    }

    public function create()
    {
        return view('main.kelas.create');
    }

    public function store(KelasStoreRequest $request)
    {
        if (auth()->user()->role === 'kepala sekolah') {
            abort(403, 'Akses ditolak.');
        }
        $data = [
            'nama' => $request->grade . ' ' . $request->nama,
            'tingkat' => $request->tingkat
        ];
        $this->kelasService->create($data);

        return redirect()->route('kelas.index')->with('success', 'Data kelas berhasil ditambahkan.');
    }

    public function show($id)
    {
        $kelas = $this->kelasService->findById(['id', 'nama', 'tingkat'], $id);

        return view('main.kelas.update', compact('kelas'));
    }

    public function update(KelasUpdateRequest $request, $id)
    {
        if (auth()->user()->role === 'kepala sekolah') {
            abort(403, 'Akses ditolak.');
        }
        $data = [
            'nama' => $request->grade . ' ' . $request->nama,
            'tingkat' => $request->tingkat
        ];

        $this->kelasService->update($id, $data);
        return redirect()->route('kelas.index')->with('success', 'Data kelas berhasil diperbarui.');
    }
}
