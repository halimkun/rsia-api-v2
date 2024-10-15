<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TambahanBiaya extends Model
{
    use HasFactory;

    protected $table = 'tambahan_biaya';

    protected $primaryKey = 'no_rawat';

    public $incrementing = false;

    public $timestamps = false;


    // cast
    protected $casts = [
        'besar_biaya' => 'float',
        'no_rawat'    => 'string',
    ];
}
