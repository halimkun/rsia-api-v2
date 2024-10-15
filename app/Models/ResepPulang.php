<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResepPulang extends Model
{
    use HasFactory;

    protected $table = 'resep_pulang';

    protected $primaryKey = 'no_rawat';

    public $incrementing = false;

    public $timestamps = false;

    // cast
    protected $casts = [
        'no_rawat' => 'string',
        'kode_brng' => 'string',
        'jml_barang' => 'integer',
        'harga' => 'float',
        'total' => 'float',
        'tanggal' => 'date',
        'jam' => 'time',    
    ];

    public function obat()
    {
        return $this->belongsTo(DataBarang::class, 'kode_brng', 'kode_brng');
    }
}
