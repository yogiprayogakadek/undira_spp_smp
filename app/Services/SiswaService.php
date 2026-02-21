<?php

namespace App\Services;

use App\Repositories\SiswaRepository;

class SiswaService
{
    public function __construct(protected SiswaRepository $siswaRepository) {}

    public function getAll(array $fields = ['*'])
    {
        return $this->siswaRepository->getAll($fields);
    }

    public function findById(array $fields = ['*'], int $id)
    {
        return $this->siswaRepository->findById($fields, $id);
    }

    public function create(array $data)
    {
        return $this->siswaRepository->create($data);
    }

    public function update(int $id, array $data)
    {
        return $this->siswaRepository->update($id, $data);
    }

    public function delete(int $id)
    {
        return $this->siswaRepository->delete($id);
    }
}
