<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bangsal extends Model
{
    use HasFactory;

    protected $table = 'bangsal';

    protected $primaryKey = 'kd_bangsal';

    protected $guarded = [];

    public $timestamps = false;

    protected $keyType = 'string';
}
