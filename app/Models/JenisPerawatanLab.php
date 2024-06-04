<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JenisPerawatanLab extends Model
{
    use HasFactory;

    protected $table = 'jns_perawatan_lab';

    protected $primaryKey = 'kd_jenis_prw';

    protected $guarded = [];

    public $timestamps = false;

    public $incrementing = false;
}
