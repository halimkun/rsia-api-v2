<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BridgingSep extends Model
{
    protected $table = 'bridging_sep';

    public $incrementing = false;

    public $timestamps = false;

    protected $casts = [
        'no_rawat' => 'string',
    ];

}
