<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Thiagoprz\CompositeKey\HasCompositeKey;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PeriksaRadiologi extends Model
{
    use HasFactory, HasCompositeKey;

    protected $table = 'periksa_radiologi';

    protected $primaryKey = ["no_rawat", "kd_jenis_prw", "tgl_periksa", "jam"];

    protected $guarded = [];
    
    protected $casts = [
        'no_rawat' => 'string',
    ];

    public $incrementing = false;

    public $timestamps = false;
}
