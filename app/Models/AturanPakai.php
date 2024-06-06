<?php

namespace App\Models;

use Awobaz\Compoships\Compoships;
use Illuminate\Database\Eloquent\Model;
use Thiagoprz\CompositeKey\HasCompositeKey;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AturanPakai extends Model
{
    use HasFactory, HasCompositeKey, Compoships;

    protected $table = 'aturan_pakai';

    protected $primaryKey = ["tgl_perawatan", "jam", "no_rawat", "kode_brng"];

    protected $guarded = [];

    protected $casts = [
        'no_rawat' => 'string',
    ];

    
    public function detailPemberianObat()
    {
        return $this->hasMany(DetailPemberianObat::class, ['tgl_perawatan', 'jam', 'no_rawat', 'kode_brng'], ['tgl_perawatan', 'jam', 'no_rawat', 'kode_brng']);
    }
}
