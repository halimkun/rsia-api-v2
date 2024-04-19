<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Departemen extends Model
{
    use HasFactory;

    protected $table = "departemen";

    protected $primaryKey = "dep_id";

    protected $guarded = [];

    protected $keyType = 'string';

    public $incrementing = false;

    public $timestamps = false;
    
    public function pegawai()
    {
        return $this->hasMany(Pegawai::class, 'departemen', 'dep_id');
    }
}
