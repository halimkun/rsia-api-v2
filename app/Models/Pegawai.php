<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pegawai extends Model
{
    use HasFactory;

    protected $table = 'pegawai';

    protected $primaryKey = 'id';

    protected $casts = [
        'nik' => 'string',
    ];

    protected $guarded = ['id'];

    
    // departemen on pegawai to dep_id on departemen
    public function dep()
    {
        return $this->belongsTo(Departemen::class, 'departemen', 'dep_id');
    }
}
