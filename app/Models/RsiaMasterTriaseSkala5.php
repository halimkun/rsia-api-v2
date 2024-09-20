<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RsiaMasterTriaseSkala5 extends Model
{
    use HasFactory;

    protected $table = 'rsia_master_triase_skala5';

    protected $primaryKey = 'kode_skala5';

    public $incrementing = false;

    public $timestamps = false;

    protected $guarded = [];

    protected $with = [
        'pemeriksaan'
    ];


    public function pemeriksaan()
    {
        return $this->belongsTo(RsiaMasterTriasePemeriksaan::class, 'kode_pemeriksaan', 'kode_pemeriksaan');
    }
}
