<?php

namespace App\Repositories;

use App\Models\Kelas;

class KelasRepository
{
    public function __construct(protected Kelas $model) {}

    public function getAll(array $fields)
    {
        return $this->model::select($fields)->get();
    }

    public function findById(array $fields, int $id)
    {
        return $this->model::select($fields)->where('id', $id)->firstOrFail();
    }

    public function create(array $data)
    {
        return $this->model::create($data);
    }

    public function update(int $id, array $data)
    {
        $user = $this->model::where('id', $id)->firstOrFail();
        return $user->update($data);
    }

    public function delete(int $id)
    {
        $user =  $this->model::find($id);
        return $user->delete();
    }

    public function getByTingkat(int $tingkat)
    {
        return $this->model::where('tingkat', $tingkat)->get();
    }
}
