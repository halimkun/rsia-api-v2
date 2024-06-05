<?php

namespace App\Models;

use Awobaz\Compoships\Compoships;
use Illuminate\Database\Eloquent\Model;
use Thiagoprz\CompositeKey\HasCompositeKey;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DetailPeriksaLab extends Model
{
    use HasFactory, HasCompositeKey, Compoships;

    protected $table = 'detail_periksa_lab';

    protected $primaryKey = ["no_rawat", "kd_jenis_prw", "tgl_periksa", "jam", "id_template"];

    protected $guarded = [];

    protected $casts = [
        'no_rawat' => 'string',
    ];

    public $incrementing = false;

    public $timestamps = false;


    public function template()
    {
        return $this->belongsTo(TemplateLaboratorium::class, 'id_template', 'id_template')
            ->select(["kd_jenis_prw", "id_template", "Pemeriksaan", "satuan", "nilai_rujukan_ld", "nilai_rujukan_la", "nilai_rujukan_pd", "nilai_rujukan_pa"]);
    }
}
