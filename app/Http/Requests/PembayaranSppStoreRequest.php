<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PembayaranSppStoreRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'siswa_id'     => 'required|integer|exists:siswa,id',
            'tanggal_bayar' => 'required|date',
            'metode_bayar' => 'required|in:tunai,transfer,qris',
            'catatan'      => 'nullable|string|max:500',
            'tagihan_ids'  => 'required|array|min:1',
            'tagihan_ids.*' => 'integer|exists:tagihan_spp,id',
        ];
    }

    public function messages(): array
    {
        return [
            'siswa_id.required'      => 'Siswa wajib dipilih.',
            'tanggal_bayar.required' => 'Tanggal bayar wajib diisi.',
            'metode_bayar.required'  => 'Metode pembayaran wajib dipilih.',
            'tagihan_ids.required'   => 'Pilih minimal satu tagihan yang akan dibayar.',
            'tagihan_ids.min'        => 'Pilih minimal satu tagihan.',
        ];
    }
}
