<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotaInap extends Model
{
    use HasFactory;

    protected $table = 'nota_inap';

    protected $primaryKey = 'no_rawat';

    protected $guarded = [];

    protected $casts = [
        'no_rawat' => 'string',
    ];

    public $incrementing = false;

    public $timestamps = false;
}