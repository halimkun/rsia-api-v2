<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RsiaKehadiranRapat extends Model
{
    use HasFactory;

    protected $table = 'rsia_kehadiran_rapat';

    protected $primaryKey = 'id';

    protected $guarded = ['id'];

    // with but only select nik ane nama
    protected $with = ['pegawai'];


    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class, 'nik', 'nik')->select('nik', 'nama');
    }
}
