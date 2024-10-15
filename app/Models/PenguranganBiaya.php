<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenguranganBiaya extends Model
{
    use HasFactory;

    protected $table = 'pengurangan_biaya';

    protected $primaryKey = 'no_rawat';

    public $incrementing = false;

    public $timestamps = false;


    // cast
    protected $casts = [
        'besar_pengurangan' => 'float',
        'no_rawat'          => 'string',
    ];
}
