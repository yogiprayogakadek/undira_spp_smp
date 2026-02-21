<?php

namespace App\Services;

use App\Repositories\TarifSppRepository;

class TarifSppService
{
    public function __construct(protected TarifSppRepository $tarifSppRepository) {}

    public function getAll(array $fields = ['*'])
    {
        return $this->tarifSppRepository->getAll($fields);
    }

    public function findById(array $fields, int $id)
    {
        return $this->tarifSppRepository->findById($fields, $id);
    }

    public function create(array $data)
    {
        return $this->tarifSppRepository->create($data);
    }

    public function update(int $id, array $data)
    {
        return $this->tarifSppRepository->update($id, $data);
    }

    public function delete(int $id)
    {
        return $this->tarifSppRepository->delete($id);
    }
}
