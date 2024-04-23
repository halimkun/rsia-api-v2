<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Thiagoprz\CompositeKey\HasCompositeKey;

class RsiaBerkasKomitePmkp extends Model
{
    use HasFactory, HasCompositeKey;

    protected $table = 'rsia_berkas_komite_pmkp';

    protected $primaryKey = ['nomor', 'tgl_terbit'];

    protected $guarded = [];


    public function exposedScopes()
    {
        return [];
    }

    public function searchableBy()
    {
        return ['perihal', 'penanggungjawab.nama'];
    }

    public function filterableBy()
    {
        return ['tgl_terbit', 'pj', 'status'];
    }

    public function sortableBy()
    {
        return ['perihal', 'tgl_terbit', 'status', 'created_at', 'updated_at'];
    }

    public function aggregatableBy()
    {
        return [];
    }

    public function includableBy()
    {
        return ['penanggungjawab'];
    }


    public function penanggungjawab()
    {
        return $this->belongsTo(Pegawai::class, 'pj', 'nik')->select('nik', 'nama');
    }
}
