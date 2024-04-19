<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JnjJabatan extends Model
{
    use HasFactory;

    protected $table = "jnj_jabatan";

    protected $primaryKey = "kode";

    protected $guarded = [];

    protected $keyType = 'string';

    public $incrementing = false;

    public $timestamps = false;

    public function pegawai()
    {
        return $this->hasMany(Pegawai::class, 'jnj_jabatan', 'kode');
    }
}
