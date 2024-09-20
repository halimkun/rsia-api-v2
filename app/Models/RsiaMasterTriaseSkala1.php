<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RsiaMasterTriaseSkala1 extends Model
{
    use HasFactory;

    protected $table = 'rsia_master_triase_skala1';

    protected $primaryKey = 'kode_skala1';

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
