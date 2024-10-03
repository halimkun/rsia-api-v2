<?php

namespace App\Models;

use Awobaz\Compoships\Compoships;
use Illuminate\Database\Eloquent\Model;
use Thiagoprz\CompositeKey\HasCompositeKey;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RsiaDataTriaseUgddetailSkala2 extends Model
{
    use HasFactory, Compoships, HasCompositeKey;

    protected $table = 'rsia_data_triase_ugddetail_skala2';

    protected $primaryKey = ['no_rawat', 'kode_skala2'];

    public $incrementing = false;

    public $timestamps = false;

    protected $guarded = [];

    protected $with = [
        'master'
    ];


    public function master()
    {
        return $this->belongsTo(RsiaMasterTriaseSkala2::class, 'kode_skala2', 'kode_skala2');
    }
}
