<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BerkasPegawai extends Model
{
    use HasFactory;

    protected $table = 'berkas_pegawai';

    protected $primaryKey = 'kode_berkas';

    protected $guarded = [];
    
    protected $keyType = 'string';
    
    public $incrementing = false;

    public $timestamps = false;
    
    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class, 'nik', 'nik');
    }
}
