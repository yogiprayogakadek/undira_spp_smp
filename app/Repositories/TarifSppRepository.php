<?php

namespace App\Repositories;

use App\Models\TarifSpp;

class TarifSppRepository
{
    public function __construct(protected TarifSpp $model) {}

    public function getAll(array $fields = ['*'])
    {
        return $this->model::select($fields)->orderBy('tingkat')->orderBy('tahun_ajaran', 'desc')->get();
    }

    public function findById(array $fields, int $id)
    {
        return $this->model::select($fields)->where('id', $id)->firstOrFail();
    }

    public function findByTingkatTahun(int $tingkat, string $tahunAjaran)
    {
        return $this->model::where('tingkat', $tingkat)->where('tahun_ajaran', $tahunAjaran)->first();
    }

    public function create(array $data)
    {
        return $this->model::create($data);
    }

    public function update(int $id, array $data)
    {
        $tarif = $this->model::where('id', $id)->firstOrFail();
        return $tarif->update($data);
    }

    public function delete(int $id)
    {
        $tarif = $this->model::find($id);
        return $tarif->delete();
    }
}
