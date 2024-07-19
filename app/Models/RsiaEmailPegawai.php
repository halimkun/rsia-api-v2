<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RsiaEmailPegawai extends Model
{
    use HasFactory;

    // table name
    protected $table = 'rsia_email_pegawai';

    // primary key
    protected $primaryKey = 'nik';

    // key type
    public $keyType = 'string';

    // guarded columns
    protected $guarded = [];

    // timestamps
    public $timestamps = false;

    
    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class, 'nik', 'nik');
    }

    public function petugas()
    {
        return $this->belongsTo(Petugas::class, 'nik', 'nip');
    }
}
