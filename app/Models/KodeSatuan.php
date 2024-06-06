<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KodeSatuan extends Model
{
    use HasFactory;

    protected $table = 'kodesatuan';

    protected $primaryKey = 'kode_sat';

    protected $guarded = [];

    protected $keyType = 'char';

    public $timestamps = false;

    public $incrementing = false;
}
