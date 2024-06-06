<?php

namespace App\Models;

use Awobaz\Compoships\Compoships;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ResepObat extends Model
{
    use HasFactory, Compoships;

    protected $table = 'resep_obat';

    protected $primaryKey = 'no_resep';

    protected $guarded = [];

    protected $casts = [
        'no_rawat' => 'string',
        'no_resep' => 'string',
    ];
    
    public $timestamps = false;

    public $incrementing = false;


    public function dokter()
    {
        return $this->belongsTo(Dokter::class, 'kd_dokter', 'kd_dokter');
    }

    public function regPeriksa()
    {
        return $this->belongsTo(RegPeriksa::class, 'no_rawat', 'no_rawat');
    }

    public function detail()
    {
        return $this->hasMany(
            DetailPemberianObat::class, 
            ["tgl_perawatan", "jam", "no_rawat"],
            ["tgl_perawatan", "jam", "no_rawat"]
        )->select(["tgl_perawatan","jam","no_rawat","jml","kode_brng","status","kd_bangsal"]);
    }
}

