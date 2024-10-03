<?php

namespace App\Models;

use Awobaz\Compoships\Compoships;
use Illuminate\Database\Eloquent\Model;
use Thiagoprz\CompositeKey\HasCompositeKey;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * App\Models\PeriksaLab
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
 * @property string $kategori
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\DetailPeriksaLab> $detailPeriksaLab
 * @property-read int|null $detail_periksa_lab_count
 * @property-read \App\Models\Dokter $dokter
 * @property-read \App\Models\JenisPerawatanLab $jenisPerawatan
 * @property-read \App\Models\Petugas $petugas
 * @property-read \App\Models\RegPeriksa $regPeriksa
 * @method static \Illuminate\Database\Eloquent\Builder|PeriksaLab newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PeriksaLab newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PeriksaLab query()
 * @method static \Illuminate\Database\Eloquent\Builder|PeriksaLab whereBagianRs($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PeriksaLab whereBhp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PeriksaLab whereBiaya($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PeriksaLab whereDokterPerujuk($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PeriksaLab whereJam($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PeriksaLab whereKategori($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PeriksaLab whereKdDokter($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PeriksaLab whereKdJenisPrw($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PeriksaLab whereKso($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PeriksaLab whereMenejemen($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PeriksaLab whereNip($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PeriksaLab whereNoRawat($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PeriksaLab whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PeriksaLab whereTarifPerujuk($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PeriksaLab whereTarifTindakanDokter($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PeriksaLab whereTarifTindakanPetugas($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PeriksaLab whereTglPeriksa($value)
 * @mixin \Eloquent
 */
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


    // scope with custom reg_periksa data
    public function scopeWithRegPeriksaPasien($query, array $regPeriksaSelect, array $pasienSelect)
    {
        return $query->with(['regPeriksa' => function ($q) use ($regPeriksaSelect, $pasienSelect) {
            $q->select($regPeriksaSelect);
            $q->with(['poliklinik', 'pasien' => function ($p) use ($pasienSelect) {
                $p->select($pasienSelect);
            }]);
        }]);
    }
    /**
     * Get the registrasi that owns the periksa lab.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */ 
    public function perujuk()
    {
        return $this->belongsTo(Dokter::class, 'dokter_perujuk', 'kd_dokter')->select('kd_dokter', 'nm_dokter');
    }

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
    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class, 'nip', 'nik')->select('id', 'nik', 'nama');
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
