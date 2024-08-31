<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JasaMedis extends Model
{
    use HasFactory;
    use \Awobaz\Compoships\Compoships;

    protected $table = 'rsia_log_jm_dokter';

    protected $primaryKey = ['bulan', 'tahun', 'kd_dokter'];

    public $incrementing = false;

    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class, 'kd_dokter', 'nik');
    }
}
