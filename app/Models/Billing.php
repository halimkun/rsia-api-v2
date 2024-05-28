<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Billing extends Model
{
    use HasFactory;

    protected $table = 'billing';

    protected $primaryKey = '';

    protected $guarded = [];

    protected $casts = [
        'no_rawat' => 'string'
    ];
    
    public $timestamps = false;

    public $incrementing = false;
}
