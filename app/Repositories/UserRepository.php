<?php

namespace App\Repositories;

use App\Models\Profile;
use App\Models\User;

class UserRepository
{
    public function __construct(protected User $model) {}

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

    public function updateOrCreateProfile(int $user_id, array $data)
    {
        return Profile::updateOrCreate(['user_id' => $user_id], $data);
    }

    public function delete(int $id)
    {
        $user =  $this->model::find($id);
        return $user->delete();
    }
}
