<?php

namespace App\Models;

use Awobaz\Compoships\Compoships;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Thiagoprz\CompositeKey\HasCompositeKey;

class DiagnosaPasien extends Model
{
    use HasFactory, HasCompositeKey, Compoships;

    protected $table = 'diagnosa_pasien';

    protected $primaryKey = ['no_rawat', 'kd_penyakit', 'status'];

    protected $guarded = [];

    public $incrementing = false;

    public $timestamps = false;

    protected $keyType = 'string';


    public function penyakit() {
        return $this->belongsTo(Penyakit::class, 'kd_penyakit', 'kd_penyakit');
    }
}
