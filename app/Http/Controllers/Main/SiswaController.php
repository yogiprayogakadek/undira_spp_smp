<?php

namespace App\Http\Controllers\Main;

use App\Http\Controllers\Controller;
use App\Http\Requests\SiswaStoreRequest;
use App\Http\Requests\SiswaUpdateRequest;
use App\Services\KelasService;
use App\Services\SiswaService;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class SiswaController extends Controller
{
    public function __construct(
        protected SiswaService $siswaService,
        protected KelasService $kelasService,
    ) {}

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $siswa = $this->siswaService->getAll(['*']);

            return DataTables::of($siswa)
                ->addIndexColumn()
                ->addColumn('kelas', function ($row) {
                    return $row->kelas?->nama ?? '—';
                })
                ->addColumn('jenis_kelamin', function ($row) {
                    return ucfirst($row->jenis_kelamin);
                })
                ->addColumn('actions', function ($row) {
                    if (auth()->user()->role === 'kepala sekolah') {
                        return '<span class="text-muted small">Read Only</span>';
                    }
                    return '
                    <a href="' . route('siswa.show', $row->id) . '">
                        <button type="button"
                            class="justify-content-center w-80 btn mb-1 bg-primary-subtle text-primary">
                            <i class="ti ti-pencil fs-4 me-2"></i>
                            Edit
                        </button>
                    </a>
                    <form action="' . route('siswa.delete', $row->id) . '" method="POST" style="display:inline"
                        onsubmit="return confirm(\'Hapus siswa ini?\')">
                        ' . csrf_field() . method_field('DELETE') . '
                        <button type="submit" class="justify-content-center w-80 btn mb-1 bg-danger-subtle text-danger">
                            <i class="ti ti-trash fs-4 me-2"></i>
                            Hapus
                        </button>
                    </form>
                    ';
                })
                ->rawColumns(['actions', 'kelas', 'jenis_kelamin'])
                ->make(true);
        }

        return view('main.siswa.index');
    }

    public function create()
    {
        $kelas = $this->kelasService->getAll(['id', 'nama', 'tingkat']);
        return view('main.siswa.create', compact('kelas'));
    }

    public function store(SiswaStoreRequest $request)
    {
        if (auth()->user()->role === 'kepala sekolah') {
            abort(403, 'Akses ditolak.');
        }
        $this->siswaService->create($request->validated());

        return redirect()->route('siswa.index')->with('success', 'Data siswa berhasil ditambahkan.');
    }

    public function show($id)
    {
        $siswa = $this->siswaService->findById(['*'], $id);
        $kelas = $this->kelasService->getAll(['id', 'nama', 'tingkat']);
        return view('main.siswa.update', compact('siswa', 'kelas'));
    }

    public function update(SiswaUpdateRequest $request, $id)
    {
        if (auth()->user()->role === 'kepala sekolah') {
            abort(403, 'Akses ditolak.');
        }
        $this->siswaService->update($id, $request->validated());

        return redirect()->route('siswa.index')->with('success', 'Data siswa berhasil diperbarui.');
    }

    public function delete($id)
    {
        if (auth()->user()->role === 'kepala sekolah') {
            abort(403, 'Akses ditolak.');
        }
        $this->siswaService->delete($id);

        return redirect()->route('siswa.index')->with('success', 'Data siswa berhasil dihapus.');
    }
}
