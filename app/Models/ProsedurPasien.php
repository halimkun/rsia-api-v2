<?php

namespace App\Models;

use Awobaz\Compoships\Compoships;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Thiagoprz\CompositeKey\HasCompositeKey;

class ProsedurPasien extends Model
{
    use HasFactory, HasCompositeKey, Compoships;

    protected $table = 'prosedur_pasien';

    protected $primaryKey = ['no_rawat', 'kode', 'status'];

    protected $guarded = [];

    public $incrementing = false;

    public $timestamps = false;

    protected $keyType = 'string';


    public function penyakit()
    {
        return $this->belongsTo(Icd9::class, 'kode', 'kode');
    }
}
