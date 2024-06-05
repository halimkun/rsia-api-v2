<?php

namespace App\Models;

use Awobaz\Compoships\Compoships;
use Illuminate\Database\Eloquent\Model;
use Thiagoprz\CompositeKey\HasCompositeKey;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PeriksaLab extends Model
{
    use HasFactory, HasCompositeKey, Compoships;

    protected $table = 'periksa_lab';

    protected $primaryKey = ["no_rawat", "kd_jenis_prw", "tgl_periksa", "jam"];

    protected $guarded = [];
    
    protected $casts = [
        'no_rawat' => 'string',
    ];

    public $incrementing = false;

    public $timestamps = false;


    /**
     * Get the registrasi that owns the periksa lab.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */ 
    public function dokter()
    {
        return $this->belongsTo(Dokter::class, 'kd_dokter', 'kd_dokter')->select('kd_dokter', 'nm_dokter');
    }

    /**
     * Get the registrasi that owns the periksa lab.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     * */ 
    public function regPeriksa()
    {
        return $this->belongsTo(RegPeriksa::class, 'no_rawat', 'no_rawat');
    }

    /**
     * Get the registrasi that owns the periksa lab.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     * */ 
    public function petugas()
    {
        return $this->belongsTo(Petugas::class, 'nip', 'nip')->select('nip', 'nama');
    }

    /**
     * Get the registrasi that owns the periksa lab.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     * */ 
    public function jenisPerawatan()
    {
        return $this->belongsTo(JenisPerawatanLab::class, 'kd_jenis_prw', 'kd_jenis_prw')
            ->select(["kd_jenis_prw", "nm_perawatan", "kd_pj", "status", "kelas", "kategori"]);
    }

    /**
     * Get the registrasi that owns the periksa lab.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     * 
    */
    public function detailPeriksaLab()
    {
        return $this->hasMany(
            DetailPeriksaLab::class,
            ['no_rawat', 'kd_jenis_prw', 'tgl_periksa', 'jam'],
            ['no_rawat', 'kd_jenis_prw', 'tgl_periksa', 'jam']
        )->select(['no_rawat', 'kd_jenis_prw', 'tgl_periksa', 'jam', 'id_template', 'nilai', 'nilai_rujukan', 'keterangan']);
    }
}
