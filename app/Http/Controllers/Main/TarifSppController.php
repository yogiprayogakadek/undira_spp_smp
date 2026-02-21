<?php

namespace App\Http\Controllers\Main;

use App\Http\Controllers\Controller;
use App\Http\Requests\TarifSppStoreRequest;
use App\Http\Requests\TarifSppUpdateRequest;
use App\Services\TarifSppService;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class TarifSppController extends Controller
{
    public function __construct(protected TarifSppService $tarifSppService) {}

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $tarif = $this->tarifSppService->getAll(['*']);

            return DataTables::of($tarif)
                ->addIndexColumn()
                ->addColumn('tingkat_label', fn ($row) => 'Kelas ' . $row->tingkat)
                ->addColumn('nominal_fmt', fn ($row) => 'Rp ' . number_format($row->nominal, 0, ',', '.'))
                ->addColumn('actions', function ($row) {
                    return '
                    <a href="' . route('tarif-spp.show', $row->id) . '">
                        <button type="button" class="justify-content-center w-80 btn mb-1 bg-primary-subtle text-primary">
                            <i class="ti ti-pencil fs-4 me-2"></i> Edit
                        </button>
                    </a>
                    <form action="' . route('tarif-spp.delete', $row->id) . '" method="POST" style="display:inline"
                        onsubmit="return confirm(\'Hapus tarif ini?\')">
                        ' . csrf_field() . method_field('DELETE') . '
                        <button type="submit" class="justify-content-center w-80 btn mb-1 bg-danger-subtle text-danger">
                            <i class="ti ti-trash fs-4 me-2"></i> Hapus
                        </button>
                    </form>
                    ';
                })
                ->rawColumns(['actions'])
                ->make(true);
        }

        return view('main.tarif_spp.index');
    }

    public function create()
    {
        return view('main.tarif_spp.create');
    }

    public function store(TarifSppStoreRequest $request)
    {
        $this->tarifSppService->create($request->validated());
        return redirect()->route('tarif-spp.index')->with('success', 'Tarif SPP berhasil ditambahkan.');
    }

    public function show($id)
    {
        $tarif = $this->tarifSppService->findById(['*'], $id);
        return view('main.tarif_spp.update', compact('tarif'));
    }

    public function update(TarifSppUpdateRequest $request, $id)
    {
        $this->tarifSppService->update($id, $request->validated());
        return redirect()->route('tarif-spp.index')->with('success', 'Tarif SPP berhasil diperbarui.');
    }

    public function delete($id)
    {
        $this->tarifSppService->delete($id);
        return redirect()->route('tarif-spp.index')->with('success', 'Tarif SPP berhasil dihapus.');
    }
}
