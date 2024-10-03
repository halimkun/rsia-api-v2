<?php

namespace App\Models;

use Awobaz\Compoships\Compoships;
use Illuminate\Database\Eloquent\Model;
use Thiagoprz\CompositeKey\HasCompositeKey;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PemeriksaanRalanKlaim extends Model
{
    use HasFactory, HasCompositeKey, Compoships;

    protected $table = 'pemeriksaan_ralan_klaim';

    protected $primaryKey = ['no_rawat', 'tgl_perawatan', 'jam_rawat'];

    public $incrementing = false;

    public $timestamps = false;
    

    public function petugas()
    {
        return $this->belongsTo(Petugas::class, 'nip', 'nip');
    }
}
