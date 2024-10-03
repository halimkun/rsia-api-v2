<?php

namespace App\Models;

use Awobaz\Compoships\Compoships;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Thiagoprz\CompositeKey\HasCompositeKey;

class CatatanPerawatan extends Model
{
    use HasFactory, HasCompositeKey, Compoships;

    protected $table = 'catatan_perawatan';

    protected $primaryKey = ['no_surat', 'tanggal', 'jam'];

    protected $guarded = [];

    public $timestamps = false;

    protected $keyType = 'string';
}
