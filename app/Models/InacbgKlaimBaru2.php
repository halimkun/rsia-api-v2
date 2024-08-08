<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InacbgKlaimBaru2 extends Model
{
    use HasFactory;

    protected $table = 'inacbg_klaim_baru2';

    protected $primaryKey = 'no_rawat';

    protected $guarded = [];

    public $timestamps = false;

    public $incrementing = false;

    protected $keyType = 'string';

    protected $casts = [
        'no_rawat' => 'string',
    ];

    public function bridgingSep()
    {
        return $this->belongsTo(BridgingSep::class, 'no_sep', 'no_sep');
    }

    public function regPeriksa()
    {
        return $this->belongsTo(RegPeriksa::class, 'no_rawat', 'no_rawat')->select('no_rawat', 'tgl_registrasi', 'jam_reg');
    }
}
