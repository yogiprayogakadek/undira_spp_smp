<?php

namespace App\Services;

use App\Repositories\UserRepository;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserService
{
    public function __construct(protected UserRepository $userRepository) {}

    public function getAll(array $fields = ['*'])
    {
        return $this->userRepository->getAll($fields);
    }

    public function findById(array $fields = ['*'], int $id)
    {
        return $this->userRepository->findById($fields, $id);
    }

    public function create(array $data)
    {
        return $this->userRepository->create($data);
    }

    public function update(int $id, array $data)
    {
        return DB::transaction(function () use ($id, $data) {
            $user = $this->userRepository->findById(['*'], $id);

            $userData = [
                'email' => $data['email'],
                'role' => $data['role'],
                'is_active' => $data['is_active'],
            ];
            if (!empty($data['password'])) {
                $userData['password'] = Hash::make($data['password']);
            }

            $this->userRepository->update($id, $userData);

            $profileFields = ['nama_lengkap', 'alamat', 'no_telp', 'nip', 'image'];
            $profileData = array_intersect_key($data, array_flip($profileFields));

            $profileData = array_filter($profileData, fn($value) => !is_null($value));

            if (isset($data['image']) && $data['image'] instanceof UploadedFile) {
                if ($user->profile && !empty($user->profile->image)) {
                    $this->deleteImage($user->profile->image);
                }
                $profileData['image'] = $this->uploadImage($data['image']);
            }

            if (!empty($profileData)) {
                $this->userRepository->updateOrCreateProfile($id, $profileData);
            }

            return $user;
        });
    }

    public function delete(int $id)
    {
        return $this->userRepository->delete($id);
    }

    public function uploadImage(UploadedFile $file)
    {
        $path = $file->store('users', 'public');
        return $path;
    }

    public function deleteImage(string $imagePath)
    {
        $relativePath = 'users/' . basename($imagePath);
        if (Storage::disk('public')->exists($relativePath)) {
            Storage::disk('public')->delete($relativePath);
        }
    }
}
