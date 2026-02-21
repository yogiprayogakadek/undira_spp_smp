<?php

namespace App\Repositories;

use App\Models\DetailPembayaran;

class DetailPembayaranRepository
{
    public function __construct(protected DetailPembayaran $model) {}

    public function create(array $data)
    {
        return $this->model::create($data);
    }

    public function createMany(array $rows)
    {
        return $this->model::insert($rows);
    }

    public function getByPembayaran(int $pembayaranId)
    {
        return $this->model::where('pembayaran_spp_id', $pembayaranId)->with('tagihan')->get();
    }

    public function deleteByPembayaran(int $pembayaranId)
    {
        return $this->model::where('pembayaran_spp_id', $pembayaranId)->delete();
    }
}
