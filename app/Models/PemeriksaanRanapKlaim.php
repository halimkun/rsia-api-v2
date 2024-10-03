<?php

namespace App\Models;

use Awobaz\Compoships\Compoships;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Thiagoprz\CompositeKey\HasCompositeKey;

class PemeriksaanRanapKlaim extends Model
{
    use HasFactory, HasCompositeKey, Compoships;

    protected $table = 'pemeriksaan_ranap_klaim';

    protected $primaryKey = ['no_rawat', 'tgl_perawatan', 'jam_rawat'];

    public $incrementing = false;

    public $timestamps = false;


    public function petugas()
    {
        return $this->belongsTo(Petugas::class, 'nip', 'nip');
    }
}
