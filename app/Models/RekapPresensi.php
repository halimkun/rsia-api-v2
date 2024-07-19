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


    public function scopeWithId($query, $nik)
    {
        $pegawai = Pegawai::select('id')->where('nik', $nik)->first();
        $id      = $pegawai->id;

        $q = $query->where('id', $id);
    }

    public function scopeWithDatang($query, $date)
    {
        $q = $query->whereDate('jam_datang', $date);
        return $q;
    }

    
    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class, 'id', 'id');
    }
}
