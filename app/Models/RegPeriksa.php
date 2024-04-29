<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RegPeriksa extends Model
{
    use HasFactory;

    protected $table = 'reg_periksa';

    protected $primaryKey = 'no_rawat';

    protected $guarded = [];

    protected $casts = [
        'no_rawat' => 'string',
        'no_rkm_medis' => 'string',
    ];


    public function pasien()
    {
        return $this->belongsTo(Pasien::class, 'no_rkm_medis', 'no_rkm_medis');
    }

    public function pasienSomeData()
    {
        return $this->belongsTo(Pasien::class, 'no_rkm_medis', 'no_rkm_medis')
            ->select('no_rkm_medis', 'nm_pasien', 'jk', 'tmp_lahir', 'tgl_lahir');
    }
}
