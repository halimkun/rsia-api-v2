<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Icd9 extends Model
{
    use HasFactory;

    protected $table = 'icd9';

    protected $primaryKey = 'kode';

    protected $guarded = [];

    public $incrementing = false;

    public $timestamps = false;

    protected $keyType = 'string';

    
    public function diagnosa()
    {
        return $this->hasMany(DiagnosaPasien::class, 'kd_penyakit', 'kode');
    }

    public function prosedur()
    {
        return $this->hasMany(ProsedurPasien::class, 'kode', 'kode');
    }
}
