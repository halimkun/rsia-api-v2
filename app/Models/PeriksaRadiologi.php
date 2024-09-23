<?php

namespace App\Models;

use Awobaz\Compoships\Compoships;
use Illuminate\Database\Eloquent\Model;
use Thiagoprz\CompositeKey\HasCompositeKey;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * App\Models\PeriksaRadiologi
 *
 * @property string $no_rawat
 * @property string $nip
 * @property string $kd_jenis_prw
 * @property string $tgl_periksa
 * @property string $jam
 * @property string $dokter_perujuk
 * @property float $bagian_rs
 * @property float $bhp
 * @property float $tarif_perujuk
 * @property float $tarif_tindakan_dokter
 * @property float $tarif_tindakan_petugas
 * @property float|null $kso
 * @property float|null $menejemen
 * @property float $biaya
 * @property string $kd_dokter
 * @property string|null $status
 * @property string $proyeksi
 * @property string $kV
 * @property string $mAS
 * @property string $FFD
 * @property string $BSF
 * @property string $inak
 * @property string $jml_penyinaran
 * @property string $dosis
 * @property-read \App\Models\Dokter $dokter
 * @property-read \App\Models\Dokter $dokterPerujuk
 * @property-read \App\Models\JenisPerawatanRadiologi $jenisPerawatan
 * @property-read \App\Models\Petugas $petugas
 * @property-read \App\Models\RegPeriksa $regPeriksa
 * @method static \Illuminate\Database\Eloquent\Builder|PeriksaRadiologi newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PeriksaRadiologi newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PeriksaRadiologi query()
 * @method static \Illuminate\Database\Eloquent\Builder|PeriksaRadiologi whereBSF($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PeriksaRadiologi whereBagianRs($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PeriksaRadiologi whereBhp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PeriksaRadiologi whereBiaya($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PeriksaRadiologi whereDokterPerujuk($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PeriksaRadiologi whereDosis($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PeriksaRadiologi whereFFD($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PeriksaRadiologi whereInak($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PeriksaRadiologi whereJam($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PeriksaRadiologi whereJmlPenyinaran($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PeriksaRadiologi whereKV($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PeriksaRadiologi whereKdDokter($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PeriksaRadiologi whereKdJenisPrw($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PeriksaRadiologi whereKso($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PeriksaRadiologi whereMAS($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PeriksaRadiologi whereMenejemen($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PeriksaRadiologi whereNip($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PeriksaRadiologi whereNoRawat($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PeriksaRadiologi whereProyeksi($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PeriksaRadiologi whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PeriksaRadiologi whereTarifPerujuk($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PeriksaRadiologi whereTarifTindakanDokter($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PeriksaRadiologi whereTarifTindakanPetugas($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PeriksaRadiologi whereTglPeriksa($value)
 * @mixin \Eloquent
 */
class PeriksaRadiologi extends Model
{
    use HasFactory, HasCompositeKey, Compoships;

    protected $table = 'periksa_radiologi';

    protected $primaryKey = ["no_rawat", "kd_jenis_prw", "tgl_periksa", "jam"];

    protected $guarded = [];
    
    protected $casts = [
        'no_rawat' => 'string',
    ];

    public $incrementing = false;

    public $timestamps = false;


    /**
     * Get the registrasi that owns the periksa radiologi.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     * */ 
    public function regPeriksa()
    {
        return $this->belongsTo(RegPeriksa::class, 'no_rawat', 'no_rawat');
    }

    /**
     * Get the petugas that owns the periksa radiologi.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     * */ 
    public function petugas()
    {
        return $this->belongsTo(Pegawai::class, 'nip', 'nik')->select('id', 'nik', 'nama');
    }

    public function hasilRadiologi()
    {
        return $this->hasOne(HasilRadiologi::class, ['tgl_periksa', 'jam'], ['tgl_periksa', 'jam']);
    }

    /**
     * Get the jenis perawatan radiologi that owns the periksa radiologi.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     * */ 
    public function jenisPerawatan()
    {
        return $this->belongsTo(JenisPerawatanRadiologi::class, 'kd_jenis_prw', 'kd_jenis_prw');
    }

    /**
     * Get the dokter perujuk that owns the periksa radiologi.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     * */ 
    public function dokterPerujuk()
    {
        return $this->belongsTo(Dokter::class, 'dokter_perujuk', 'kd_dokter')->select('kd_dokter', 'nm_dokter');
    }

    /**
     * Get the dokter that owns the periksa radiologi.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     * */ 
    public function dokter()
    {
        return $this->belongsTo(Dokter::class, 'kd_dokter', 'kd_dokter')->select('kd_dokter', 'nm_dokter');
    }
}
