<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RsiaSuratMasuk extends Model
{
    use HasFactory;

    protected $table = 'rsia_surat_masuk';

    protected $primaryKey = 'no';

    protected $guarded = ['no'];

    public $timestamps = false;

    protected $casts = [
        'nosimrs' => 'date',
        'no_surat' => 'string',
    ];
}
