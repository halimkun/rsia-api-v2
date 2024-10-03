<?php

namespace App\Models;

use Awobaz\Compoships\Compoships;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Thiagoprz\CompositeKey\HasCompositeKey;

class RsiaDataTriaseUgddetailSkala1 extends Model
{
    use HasFactory, Compoships, HasCompositeKey;

    protected $table = 'rsia_data_triase_ugddetail_skala1';

    protected $primaryKey = ['no_rawat', 'kode_skala1'];

    public $incrementing = false;

    public $timestamps = false;

    protected $guarded = [];

    protected $with = [
        'master'
    ];

    public function master()
    {
        return $this->belongsTo(RsiaMasterTriaseSkala1::class, 'kode_skala1', 'kode_skala1');
    }
}
