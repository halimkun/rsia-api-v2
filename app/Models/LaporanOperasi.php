<?php

namespace App\Models;

use Awobaz\Compoships\Compoships;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Thiagoprz\CompositeKey\HasCompositeKey;

class LaporanOperasi extends Model
{
    use HasFactory, Compoships, HasCompositeKey;

    protected $table = 'laporan_operasi';

    protected $primaryKey = ['no_rawat', 'tanggal'];

    public $incrementing = false;

    public $timestamps = false;

    protected $keyType = 'string';
}
