<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TarifSppStoreRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'tingkat'      => 'required|integer|in:7,8,9',
            'tahun_ajaran' => ['required', 'string', 'regex:/^\d{4}\/\d{4}$/',
                function ($attribute, $value, $fail) {
                    $exists = \App\Models\TarifSpp::where('tingkat', $this->tingkat)
                        ->where('tahun_ajaran', $value)->exists();
                    if ($exists) {
                        $fail("Tarif untuk Kelas {$this->tingkat} tahun ajaran {$value} sudah ada.");
                    }
                }
            ],
            'nominal'      => 'required|numeric|min:1',
            'keterangan'   => 'nullable|string|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'tingkat.required'      => 'Tingkat kelas wajib dipilih.',
            'tingkat.in'            => 'Tingkat hanya boleh 7, 8, atau 9.',
            'tahun_ajaran.required' => 'Tahun ajaran wajib diisi.',
            'tahun_ajaran.regex'    => 'Format tahun ajaran: 2025/2026.',
            'nominal.required'      => 'Nominal SPP wajib diisi.',
            'nominal.min'           => 'Nominal harus lebih dari 0.',
        ];
    }
}
