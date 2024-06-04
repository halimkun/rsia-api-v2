<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataBarang extends Model
{
    use HasFactory;

    protected $table = 'databarang';

    protected $primaryKey = 'kode_brng';

    protected $guarded = [];

    public $incrementing = false;

    public $timestamps = false;
}
