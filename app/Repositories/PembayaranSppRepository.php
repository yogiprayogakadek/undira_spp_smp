<?php

namespace App\Repositories;

use App\Models\PembayaranSpp;

class PembayaranSppRepository
{
    public function __construct(protected PembayaranSpp $model) {}

    public function getAll(array $fields = ['*'])
    {
        return $this->model::select($fields)
            ->with(['siswa.kelas', 'user', 'detail.tagihan'])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function findById(array $fields, int $id)
    {
        return $this->model::select($fields)
            ->with(['siswa.kelas', 'user', 'detail.tagihan'])
            ->where('id', $id)
            ->firstOrFail();
    }

    public function findByNoKwitansi(string $noKwitansi)
    {
        return $this->model::where('no_kwitansi', $noKwitansi)
            ->with(['siswa.kelas', 'user', 'detail.tagihan'])
            ->firstOrFail();
    }

    public function create(array $data)
    {
        return $this->model::create($data);
    }

    public function delete(int $id)
    {
        $pembayaran = $this->model::find($id);
        return $pembayaran?->delete();
    }
}
