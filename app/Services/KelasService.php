<?php

namespace App\Services;

use App\Repositories\KelasRepository;

class KelasService
{
    public function __construct(protected KelasRepository $kelasRepository) {}

    public function getAll(array $fields = ['*'])
    {
        return $this->kelasRepository->getAll($fields);
    }

    public function findById(array $fields = ['*'], int $id)
    {
        return $this->kelasRepository->findById($fields, $id);
    }

    public function create(array $data)
    {
        return $this->kelasRepository->create($data);
    }

    public function update(int $id, array $data)
    {
        return $this->kelasRepository->update($id, $data);
    }

    public function delete(int $id)
    {
        return $this->kelasRepository->delete($id);
    }

    public function getByTingkat(int $tingkat)
    {
        return $this->kelasRepository->getByTingkat($tingkat);
    }
}
