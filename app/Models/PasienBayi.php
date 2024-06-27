<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PasienBayi extends Model
{
    use HasFactory;

    protected $table = 'pasien_bayi';

    protected $primaryKey = 'no_rkm_medis';

    protected $guarded = [];

    protected $casts = [
        'no_rkm_medis' => 'string',
        'penolong'     => 'string',
        'no_skl'       => 'string',
    ];
    
    public $timestamps = false;

    public $incrementing = false;
}
