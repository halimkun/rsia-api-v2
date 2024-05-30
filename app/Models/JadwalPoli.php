<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Thiagoprz\CompositeKey\HasCompositeKey;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class JadwalPoli extends Model
{
    use HasFactory, HasCompositeKey;

    protected $table = 'jadwal';

    protected $primaryKey = ['kd_dokter', 'hari_kerja', 'jam_mulai'];

    // protected $guarded = [];

    public $timestamps = false;

    public $incrementing = false;

    
    public function poliklinik()
    {
        return $this->belongsTo(Poliklinik::class, 'kd_poli', 'kd_poli');
    }

    public function dokter()
    {
        return $this->belongsTo(Dokter::class, 'kd_dokter', 'kd_dokter');
    }
}
