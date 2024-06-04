<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Thiagoprz\CompositeKey\HasCompositeKey;

class PemeriksaanRalan extends Model
{
    use HasFactory, HasCompositeKey;

    protected $table = 'pemeriksaan_ralan';

    protected $primaryKey = ['no_rawat', 'tgl_perawatan', 'jam_rawat'];

    protected $guarded = [];

    protected $casts = [
        'no_rawat' => 'string',
        'no_resep' => 'string',
    ];

    public $timestamps = false;

    public $incrementing = false;


    public function regPeriksa()
    {
        return $this->belongsTo(RegPeriksa::class, 'no_rawat', 'no_rawat');
    }

    public function petugas()
    {
        return $this->belongsTo(Petugas::class, 'nip', 'nip')->select('nip', 'nama');
    }
}
