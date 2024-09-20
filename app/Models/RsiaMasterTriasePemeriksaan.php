<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RsiaMasterTriasePemeriksaan extends Model
{
    use HasFactory;

    protected $table = 'rsia_master_triase_pemeriksaan';

    protected $primaryKey = 'kode_pemeriksaan';

    public $incrementing = false;

    public $timestamps = false;

    protected $guarded = [];
}
