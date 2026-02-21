<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SiswaUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('id');

        return [
            'kelas_id'      => 'required|integer|exists:kelas,id',
            'nama_lengkap'  => 'required|string|max:150',
            'nis'           => ['required', 'string', 'max:20', Rule::unique('siswa', 'nis')->ignore($id)],
            'tempat_lahir'  => 'required|string|max:100',
            'tanggal_lahir' => 'required|date',
            'agama'         => 'required|in:Islam,Kristen,Katolik,Hindu,Budha,Konghucu',
            'jenis_kelamin' => 'required|in:laki-laki,perempuan',
            'email'         => 'nullable|email|max:150',
            'no_telp'       => 'required|string|max:20',
            'alamat'        => 'required|string|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'kelas_id.required'      => 'Kelas wajib dipilih.',
            'kelas_id.exists'        => 'Kelas yang dipilih tidak valid.',
            'nama_lengkap.required'  => 'Nama lengkap wajib diisi.',
            'nis.required'           => 'NIS wajib diisi.',
            'nis.unique'             => 'NIS sudah digunakan oleh siswa lain.',
            'tempat_lahir.required'  => 'Tempat lahir wajib diisi.',
            'tanggal_lahir.required' => 'Tanggal lahir wajib diisi.',
            'agama.required'         => 'Agama wajib dipilih.',
            'jenis_kelamin.required' => 'Jenis kelamin wajib dipilih.',
            'no_telp.required'       => 'Nomor telepon wajib diisi.',
            'alamat.required'        => 'Alamat wajib diisi.',
        ];
    }
}
