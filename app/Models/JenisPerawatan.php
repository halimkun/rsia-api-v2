<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JenisPerawatan extends Model
{
    use HasFactory;

    protected $table = 'jns_perawatan';

    protected $primaryKey = 'kd_jenis_prw';

    protected $guarded = [];

    public $timestamps = false;

    public $incrementing = false;

    public function kategori()
    {
        return $this->belongsTo(KategoriPerawatan::class, 'kd_kategori', 'kd_kategori');
    }
}
