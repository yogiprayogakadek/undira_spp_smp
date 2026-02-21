<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TarifSppUpdateRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        $id = $this->route('id');

        return [
            'tingkat'      => 'required|integer|in:7,8,9',
            'tahun_ajaran' => ['required', 'string', 'regex:/^\d{4}\/\d{4}$/',
                function ($attribute, $value, $fail) use ($id) {
                    $exists = \App\Models\TarifSpp::where('tingkat', $this->tingkat)
                        ->where('tahun_ajaran', $value)
                        ->where('id', '!=', $id)
                        ->exists();
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
            'tahun_ajaran.required' => 'Tahun ajaran wajib diisi.',
            'tahun_ajaran.regex'    => 'Format tahun ajaran: 2025/2026.',
            'nominal.required'      => 'Nominal SPP wajib diisi.',
        ];
    }
}
