<?php

namespace App\Models;

use Awobaz\Compoships\Compoships;
use Illuminate\Database\Eloquent\Model;
use Thiagoprz\CompositeKey\HasCompositeKey;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RsiaDataTriaseUgddetailSkala5 extends Model
{
    use HasFactory, Compoships, HasCompositeKey;

    protected $table = 'rsia_data_triase_ugddetail_skala5';

    protected $primaryKey = ['no_rawat', 'kode_skala5'];

    public $incrementing = false;

    public $timestamps = false;

    protected $guarded = [];

    protected $with = [
        'master'
    ];
    

    public function master()
    {
        return $this->belongsTo(RsiaMasterTriaseSkala5::class, 'kode_skala5', 'kode_skala5');
    }
}
