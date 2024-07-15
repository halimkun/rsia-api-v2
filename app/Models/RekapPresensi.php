<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Thiagoprz\CompositeKey\HasCompositeKey;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RekapPresensi extends Model
{
    use HasFactory, HasCompositeKey;

    protected $table = 'rekap_presensi';

    protected $primaryKey = ['id', 'jam_datang'];

    protected $guarded = [];


    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class, 'id', 'id');
    }
}
