<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KategoriPerawatan extends Model
{
    use HasFactory;

    protected $table = 'kategori_perawatan';

    protected $primaryKey = 'kd_kategori';

    protected $guarded = [];

    public $timestamps = false;

    public $incrementing = false;

    public function jenisPerawatanInap()
    {
        return $this->hasMany(JenisPerawatanInap::class, 'kategori', 'kd_kategori');
    }
}
