<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Spesialis extends Model
{
    use HasFactory;

    protected $table = 'spesialis';

    protected $primaryKey = 'kd_sps';

    protected $keyType = 'string';
        
    // protected $guarded = [];

    public $incrementing = false;

    public $timestamps = false;
}
