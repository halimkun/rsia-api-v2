<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BridgingSuratPriBpjs extends Model
{
    use HasFactory;

    protected $table = 'bridging_surat_pri_bpjs';

    protected $primaryKey = 'no_rawat';

    protected $guarded = [];

    public $timestamps = false;

    protected $keyType = 'string';

}
