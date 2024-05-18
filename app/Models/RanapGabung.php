<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RanapGabung extends Model
{
    use HasFactory;

    protected $table = 'ranap_gabung';

    // protected $guarded = ['id'];

    // protected $with = ['menu'];

    public $timestamps = false;

    public $incrementing = false;
}
