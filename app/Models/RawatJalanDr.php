<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Thiagoprz\CompositeKey\HasCompositeKey;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RawatJalanDr extends Model
{
    use HasFactory, HasCompositeKey;

    protected $table = 'rawat_jl_dr';

    protected $primaryKey = ["no_rawat", "kd_jenis_prw", "kd_dokter", "tgl_perawatan", "jam_rawat"];

    protected $guarded = [];

    protected $casts = [
        'no_rawat' => 'string',
    ];

    public $incrementing = false;

    public $timestamps = false;
}
