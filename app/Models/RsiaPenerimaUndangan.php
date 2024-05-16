<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Thiagoprz\CompositeKey\HasCompositeKey;

class RsiaPenerimaUndangan extends Model
{
    use HasCompositeKey;

    protected $table = 'rsia_penerima_undangan';

    protected $primaryKey = ['no_surat', 'penerima', 'tipe'];

    public $incrementing = false;

    public $timestamps = false;

    protected $guarded = [];

    protected $casts = [
        'no_surat' => 'string',
        'penerima' => 'string',
        'tipe' => 'string',
        'model' => 'string',
    ];

    // detail penerima
    public function detail()
    {
        return $this->belongsTo(Pegawai::class, 'penerima', 'nik')->select('nik', 'nama', 'jbtn', 'departemen');
    }
}
