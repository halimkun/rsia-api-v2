<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResikoKerja extends Model
{
    use HasFactory;

    protected $table = "resiko_kerja";

    protected $primaryKey = "kode_resiko";

    protected $guarded = [];

    protected $keyType = 'string';

    public $incrementing = false;

    public $timestamps = false;

    public function pegawai()
    {
        return $this->hasMany(Pegawai::class, 'kode_resiko', 'kode_resiko');
    }
}
