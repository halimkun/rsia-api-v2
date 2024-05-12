<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RsiaPks extends Model
{
    use HasFactory;

    protected $table = 'rsia_pks';

    protected $primaryKey = 'id';

    protected $guarded = ['id'];

    public $timestamps = false;


    public function penanggungJawab()
    {
        return $this->belongsTo(Pegawai::class, 'pj', 'nik')->select('nik', 'nama');
    }
}
