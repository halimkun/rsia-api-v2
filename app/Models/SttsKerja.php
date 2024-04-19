<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SttsKerja extends Model
{
    use HasFactory;

    protected $table = "stts_kerja";

    protected $primaryKey = "stts";

    protected $guarded = [];

    protected $keyType = 'string';

    public $incrementing = false;

    public $timestamps = false;

    public function pegawai()
    {
        return $this->hasMany(Pegawai::class, 'stts_kerja', 'stts');
    }
}
