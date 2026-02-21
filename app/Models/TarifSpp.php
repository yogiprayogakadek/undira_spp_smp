<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TarifSpp extends Model
{
    protected $table = 'tarif_spp';
    protected $fillable = [
        'tingkat',
        'tahun_ajaran',
        'nominal',
        'keterangan',
    ];

    protected $casts = [
        'nominal' => 'decimal:2',
    ];

    public function tagihan()
    {
        return $this->hasMany(TagihanSpp::class, 'tarif_spp_id', 'id');
    }
}
