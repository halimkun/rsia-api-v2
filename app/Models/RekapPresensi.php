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
        return $q;
    }

    public function scopeWithDatang($query, $date)
    {
        return $query->whereDate('jam_datang', $date);
    }

    public function scopeWithPulang($query, $date)
    {
        return $query->whereDate('jam_pulang', $date);
    }

    // beetwen
    public function scopeWithRange($query, $start, $end)
    {
        return $query->whereBetween('jam_datang', [$start, $end]);
    }
    
    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class, 'id', 'id');
    }
}
