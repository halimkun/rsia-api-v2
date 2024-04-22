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


    public function exposedScopes()
    {
        return [];
    }

    public function searchableBy()
    {
        return ['no_surat', 'pengirim', 'perihal', 'pelaksanaan', 'tempat'];
    }

    public function filterableBy()
    {
        return ['no_surat', 'tgl_surat', 'pelaksanaan', 'ket',];
    }

    public function sortableBy()
    {
        return ['no', 'no_simrs', 'perihal', 'pengirim', 'tgl_surat', 'pelaksanaan'];
    }

    public function aggregatableBy()
    {
        return [];
    }

    public function includableBy()
    {
        return [];
    }
}
