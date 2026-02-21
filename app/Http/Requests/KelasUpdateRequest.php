<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;

class KelasUpdateRequest extends FormRequest
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
        return [
            'grade' => 'required|in:7,8,9',
            'nama' => [
                'required',
                'string',
                'max:50',
                function ($attribute, $value, $fail) {
                    $combine = $this->grade . ' ' . $value;
                    $id = $this->route('id');
                    $exists = DB::table('kelas')
                        ->where('nama', $combine)
                        ->where('id', '!=', $id)
                        ->exists();

                    if ($exists) {
                        $fail("Kelas {$combine} sudah digunakan oleh data lain.");
                    }
                }
            ],
            'tingkat' => 'required|integer|in:7,8,9',
        ];
    }

    public function messages(): array
    {
        return [
            'grade.required' => 'Tingkatan (Grade) wajib dipilih.',
            'nama.required'  => 'Nama kelas wajib diisi.',
            'tingkat.required' => 'Tingkat kelas wajib dipilih.',
        ];
    }
}
