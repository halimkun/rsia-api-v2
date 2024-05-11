<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Thiagoprz\CompositeKey\HasCompositeKey;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RsiaSk extends Model
{
    use HasFactory, HasCompositeKey;

    protected $table = 'rsia_sk';

    protected $primaryKey = ['nomor', 'jenis', 'tgl_terbit'];

    protected $guarded = [];

    public $timestamps = false;


    public function penanggungJawab()
    {
        return $this->belongsTo(Pegawai::class, 'pj', 'nik')->select('nik', 'nama');
    }
}
