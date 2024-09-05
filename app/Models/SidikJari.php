<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SidikJari extends Model
{
    use HasFactory;

    protected $table = 'sidikjari';

    protected $primaryKey = 'id';

    public $incrementing = false;

    public $timestamps = false;

    // protected $guarded = [];

    protected $casts = [
        'id' => 'string',
    ];
}
