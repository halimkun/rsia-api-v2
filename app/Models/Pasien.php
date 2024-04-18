<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Laravel\Passport\HasApiTokens;

class Pasien extends Authenticatable
{
    use HasFactory, HasApiTokens;

    protected $table = 'pasien';

    protected $primaryKey = 'no_rkm_medis';

    protected $hidden = ['no_ktp', 'no_peserta'];

    protected $guarded = [];

    public $timestamps = false;

    protected $casts = [
        'no_rkm_medis' => 'string',
        'kd_pj' => 'string',
    ];
}
