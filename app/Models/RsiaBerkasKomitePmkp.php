<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Thiagoprz\CompositeKey\HasCompositeKey;

class RsiaBerkasKomitePmkp extends Model
{
    use HasFactory, HasCompositeKey;

    protected $table = 'rsia_berkas_komite_pmkp';

    protected $primaryKey = ['nomor', 'tgl_terbit'];

    protected $guarded = [];


    public function penanggungjawab()
    {
        return $this->belongsTo(Pegawai::class, 'pj', 'nik')->select('nik', 'nama');
    }
}
