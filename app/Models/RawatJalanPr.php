<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Thiagoprz\CompositeKey\HasCompositeKey;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RawatJalanPr extends Model
{
    use HasFactory, HasCompositeKey;

    protected $table = 'rawat_jl_pr';

    protected $primaryKey = ["no_rawat", "kd_jenis_prw", "nip", "tgl_perawatan", "jam_rawat"];

    protected $guarded = [];
    
    protected $casts = [
        'no_rawat' => 'string',
    ];

    public $incrementing = false;

    public $timestamps = false;
}