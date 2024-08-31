<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JasaPelayanan extends Model
{
    use HasFactory;
    use \Awobaz\Compoships\Compoships;

    protected $table = 'rsia_log_jm';

    protected $primaryKey = ['bulan', 'tahun', 'nik'];

    public $incrementing = false;

    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class, 'nik', 'nik');
    }

    public function jasa_pelayanan_akun()
    {
        return $this->belongsTo(
            JasaPelayananAkun::class,
            ['tahun', 'bulan'],
            ['tahun', 'bulan']
        );
    }
}
