<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dokter extends Model
{
    use HasFactory;

    protected $table = 'dokter';

    protected $primaryKey = 'kd_dokter';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $casts = [
        'kd_dokter' => 'string',
    ];

    protected $guarded = [];
}
