<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RsiaNaikKelas extends Model
{
    use HasFactory;

    protected $table = 'rsia_naik_kelas';

    protected $primaryKey = 'no_sep';

    public $incrementing = false;

    public $timestamps = false;

    protected $keyType = 'string';   

    protected $guarded = [];    
}
