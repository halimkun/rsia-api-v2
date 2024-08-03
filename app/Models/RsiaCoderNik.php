<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RsiaCoderNik extends Model
{
    use HasFactory;

    protected $table = 'inacbg_coder_nik';

    protected $guarded = [];

    protected $primaryKey = 'nik';

    protected $keyType = 'string';

    public $incrementing = false;

    public $timestamps = false;


    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class, 'nik', 'nik');
    }
}
