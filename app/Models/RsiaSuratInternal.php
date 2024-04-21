<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RsiaSuratInternal extends Model
{
    use HasFactory;

    protected $table = 'rsia_surat_internal';

    protected $primaryKey = 'no_surat';

    protected $keyType = 'string';

    protected $casts = [
        'no_surat' => 'string',
    ];

    protected $guarded = [];

    public $incrementing = false;

    public $timestamps = false;


    public function penanggung_jawab()
    {
        return $this->belongsTo(Pegawai::class, 'pj', 'nik');
    }
}
