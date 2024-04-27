<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Thiagoprz\CompositeKey\HasCompositeKey;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Operasi extends Model
{
    use HasFactory, HasCompositeKey;

    protected $table = 'operasi';

    protected $primaryKey = ["no_rawat", "tgl_operasi", "kode_paket"];

    protected $guarded = [];

    protected $casts = [
        'no_rawat' => 'string',
    ];

    public $incrementing = false;

    public $timestamps = false;
}
