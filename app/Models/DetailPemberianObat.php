<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Thiagoprz\CompositeKey\HasCompositeKey;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DetailPemberianObat extends Model
{
    use HasFactory, HasCompositeKey;

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
}
