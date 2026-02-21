<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $userId = $this->route('id');
        return [
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($userId),
            ],
            'role' => 'required|in:admin,kepala sekolah,bendahara',
            'password' => 'nullable|min:8|confirmed',
            'is_active' => 'required|in:1,0',

            // Profile Update
            'nama_lengkap' => 'nullable|max:100|string|min:3',
            'alamat' => 'nullable|max:200|string|min:3',
            'no_telp' => [
                'nullable',
                'regex:/^(?:\+62|62|0)8[1-9][0-9]{7,10}$/',
                'min:10',
                'max:15'
            ],
            'nip' => [
                'nullable',
                'numeric',
                'digits_between:8,20',
                Rule::unique('profiles', 'nip')->ignore($userId, 'user_id'),
            ],
            'image' => 'nullable|image|mimes:png,jpg,jpeg,jfif|max:2048',
        ];
    }

    public function messages(): array
    {
        return [
            'email.required'    => 'Email wajib diisi.',
            'email.unique'      => 'Email sudah digunakan oleh user lain.',
            'role.required'     => 'Role wajib dipilih.',
            'role.in'           => 'Role harus salah satu dari: admin, kepala sekolah, atau bendahara.',
            'password.confirmed' => 'Konfirmasi password baru tidak cocok.',
            'password.min'       => 'Password minimal harus 8 karakter.',
            'is_active.required' => 'Status aktif wajib dipilih.',

            'no_telp.regex'     => 'Format nomor telepon tidak valid (Gunakan format 08... atau +62...).',
            'nip.numeric'       => 'NIP harus berupa angka.',
            'nip.digits_between' => 'NIP harus diantara 8 sampai 20 digit.',
            'nip.unique'        => 'NIP sudah terdaftar di sistem.',
            'image.image'       => 'File yang diunggah harus berupa gambar.',
            'image.max'         => 'Ukuran gambar maksimal adalah 2MB.',
        ];
    }
}
