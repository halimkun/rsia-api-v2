<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BerkasDigitalPerawatan extends Model
{
    use HasFactory;

    protected $table = 'berkas_digital_perawatan';

    protected $fillable = ["no_rawat", "kode", "lokasi_file"];

    protected $keyType = 'string';

    protected $primaryKey = 'no_rawat';
    
    public $timestamps = false;

    public $incrementing = false;
}
