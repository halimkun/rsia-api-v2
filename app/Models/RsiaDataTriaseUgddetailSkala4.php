<?php

namespace App\Models;

use Awobaz\Compoships\Compoships;
use Illuminate\Database\Eloquent\Model;
use Thiagoprz\CompositeKey\HasCompositeKey;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RsiaDataTriaseUgddetailSkala4 extends Model
{
    use HasFactory, Compoships, HasCompositeKey;

    protected $table = 'rsia_data_triase_ugddetail_skala4';

    protected $primaryKey = ['no_rawat', 'kode_skala4'];

    public $incrementing = false;

    public $timestamps = false;

    protected $guarded = [];

    protected $with = [
        'master'
    ];

    public function master()
    {
        return $this->belongsTo(RsiaMasterTriaseSkala4::class, 'kode_skala4', 'kode_skala4');
    }
}
