<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pegawai extends Model
{
    use HasFactory;

    protected $table = 'pegawai';

    protected $primaryKey = 'nik';

    public $incrementing = false;

    public $timestamps = false;

    protected $keyType = 'string';

    protected $casts = [
        'nik' => 'string',
    ];

    protected $guarded = ['id'];

    protected $hidden = ['id'];


    // departemen on pegawai to dep_id on departemen
    public function dep()
    {
        return $this->belongsTo(Departemen::class, 'departemen', 'dep_id');
    }

    public function berkas()
    {
        return $this->hasMany(BerkasPegawai::class, 'nik', 'nik');
    }
}
