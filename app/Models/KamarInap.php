<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Thiagoprz\CompositeKey\HasCompositeKey;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class KamarInap extends Model
{
    use HasFactory, HasCompositeKey;

    protected $table = 'kamar_inap';

    protected $primaryKey = ["no_rawat", "tgl_masuk", "jam_masuk"];

    protected $guarded = [];

    protected $casts = [
        'no_rawat' => 'string',
    ];

    public $incrementing = false;

    public $timestamps = false;

    
    public function regPeriksa()
    {
        return $this->belongsTo(RegPeriksa::class, 'no_rawat', 'no_rawat');
    }

    public function pasien()
    {
        return $this->hasOneThrough(Pasien::class, RegPeriksa::class, 'no_rawat', 'no_rkm_medis', 'no_rawat', 'no_rkm_medis')
            ->select('pasien.no_rkm_medis', 'pasien.nm_pasien', 'pasien.jk', 'pasien.tmp_lahir', 'pasien.tgl_lahir');
    }
}
