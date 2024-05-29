<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Poliklinik extends Model
{
    use HasFactory;

    protected $table = 'poliklinik';

    protected $primaryKey = 'kd_poli';

    protected $guarded = [];

    public $incrementing = false;
    
    public $timestamps = false;


    public function jadwal_dokter()
    {
        return $this->hasMany(JadwalPoli::class, 'kd_poli', 'kd_poli');
    }
}
