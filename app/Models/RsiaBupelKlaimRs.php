<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RsiaBupelKlaimRs extends Model
{
    use HasFactory;

    protected $table = 'rsia_bupel_klaim_rs';

    protected $primaryKey = 'bulan';

    public $incrementing = false;

    public $timestamps = false;

    protected $guarded = [];
}
