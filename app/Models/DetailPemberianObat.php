<?php

namespace App\Models;

use Awobaz\Compoships\Compoships;
use Illuminate\Database\Eloquent\Model;
use Thiagoprz\CompositeKey\HasCompositeKey;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DetailPemberianObat extends Model
{
    use HasFactory, HasCompositeKey, Compoships;

    protected $table = 'detail_pemberian_obat';

    protected $primaryKey = ["tgl_perawatan", "jam", "no_rawat", "kode_brng", "no_batch", "no_faktur"];

    protected $guarded = [];

    protected $casts = [
        'no_rawat' => 'string',
    ];

    public $incrementing = false;

    public $timestamps = false;


    public function getTotal()
    {
        return $this->total ?? 0;
    }

    public function regPeriksa()
    {
        return $this->belongsTo(RegPeriksa::class, 'no_rawat', 'no_rawat');
    }

    public function obat()
    {
        return $this->belongsTo(DataBarang::class, 'kode_brng', 'kode_brng')->select('kode_brng', 'nama_brng');
    }
}
