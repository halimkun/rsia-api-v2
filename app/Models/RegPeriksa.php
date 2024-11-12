<?php

namespace App\Models;

use Awobaz\Compoships\Compoships;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * App\Models\RegPeriksa
 *
 * @property string|null $no_reg
 * @property string $no_rawat
 * @property string|null $tgl_registrasi
 * @property string|null $jam_reg
 * @property string|null $kd_dokter
 * @property string|null $no_rkm_medis
 * @property string|null $kd_poli
 * @property string|null $p_jawab
 * @property string|null $almt_pj
 * @property string|null $hubunganpj
 * @property float|null $biaya_reg
 * @property string|null $stts
 * @property string $stts_daftar
 * @property string $status_lanjut
 * @property string $kd_pj
 * @property int|null $umurdaftar
 * @property string|null $sttsumur
 * @property string $status_bayar
 * @property string $status_poli
 * @property-read \App\Models\Penjab $caraBayar
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\DetailPemberianObat> $detailPemberianObat
 * @property-read int|null $detail_pemberian_obat_count
 * @property-read \App\Models\Dokter|null $dokter
 * @property-read \App\Models\Pasien|null $pasien
 * @property-read \App\Models\Pasien|null $pasienSomeData
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\PemeriksaanRalan> $pemeriksaanRalan
 * @property-read int|null $pemeriksaan_ralan_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\PemeriksaanRanap> $pemeriksaanRanap
 * @property-read int|null $pemeriksaan_ranap_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\PeriksaLab> $periksaLab
 * @property-read int|null $periksa_lab_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\PeriksaRadiologi> $periksaRadiologi
 * @property-read int|null $periksa_radiologi_count
 * @property-read \App\Models\Poliklinik|null $poliklinik
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ResepObat> $resepObat
 * @property-read int|null $resep_obat_count
 * @method static \Illuminate\Database\Eloquent\Builder|RegPeriksa newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RegPeriksa newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RegPeriksa query()
 * @method static \Illuminate\Database\Eloquent\Builder|RegPeriksa whereAlmtPj($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RegPeriksa whereBiayaReg($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RegPeriksa whereHubunganpj($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RegPeriksa whereJamReg($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RegPeriksa whereKdDokter($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RegPeriksa whereKdPj($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RegPeriksa whereKdPoli($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RegPeriksa whereNoRawat($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RegPeriksa whereNoReg($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RegPeriksa whereNoRkmMedis($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RegPeriksa wherePJawab($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RegPeriksa whereStatusBayar($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RegPeriksa whereStatusLanjut($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RegPeriksa whereStatusPoli($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RegPeriksa whereStts($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RegPeriksa whereSttsDaftar($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RegPeriksa whereSttsumur($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RegPeriksa whereTglRegistrasi($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RegPeriksa whereUmurdaftar($value)
 * @mixin \Eloquent
 */
class RegPeriksa extends Model
{
    use HasFactory, Compoships;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'reg_periksa';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'no_rawat';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * Indicates if the model should be incrementing.
     * 
     * @var bool
     * */
    public $incrementing = false;

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'no_rawat' => 'string',
        'no_rkm_medis' => 'string',
    ];


    public function scopeSepData($query, $fields)
    {
        return $query->with(['sep' => function ($query) use ($fields) {
            $query->select($fields);
        }]);
    }

    public function scopePemeriksaanRalanData($query, $fields)
    {
        return $query->with(['pemeriksaanRalan' => function ($query) use ($fields) {
            $query->select($fields);
        }]);
    }

    public function scopePemeriksaanRanapData($query, $fields)
    {
        return $query->with(['pemeriksaanRanap' => function ($query) use ($fields) {
            $query->select($fields);
        }]);
    }


    /**
     * Get the pasien that owns the registrasi.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function pasien()
    {
        return $this->belongsTo(Pasien::class, 'no_rkm_medis', 'no_rkm_medis')
            ->select('no_rkm_medis', 'nm_pasien', 'jk', 'tmp_lahir', 'tgl_lahir', 'alamat', 'no_tlp', 'no_ktp', 'agama', 'pekerjaan');
    }

    /**
     * Get the pasien that owns the registrasi.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function pasienSomeData()
    {
        return $this->belongsTo(Pasien::class, 'no_rkm_medis', 'no_rkm_medis')
            ->select('no_rkm_medis', 'nm_pasien', 'jk', 'tmp_lahir', 'tgl_lahir');
    }

    /**
     * Get the dokter that owns the registrasi.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function dokter()
    {
        return $this->belongsTo(Dokter::class, 'kd_dokter', 'kd_dokter')->select('kd_dokter', 'nm_dokter', 'kd_sps');
    }

    /**
     * Get the poliklinik that owns the registrasi.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     * 
     */
    public function poliklinik()
    {
        return $this->belongsTo(Poliklinik::class, 'kd_poli', 'kd_poli');
    }

    /**
     * Get the poliklinik that owns the registrasi.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function caraBayar()
    {
        return $this->belongsTo(Penjab::class, 'kd_pj', 'kd_pj');
    }

    /**
     * Get the resep obat that owns the registrasi.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function resepObat()
    {
        return $this->hasMany(ResepObat::class, 'no_rawat', 'no_rawat');
    }

    /**
     * Get the pemeriksaan ranap that owns the registrasi.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     * */
    public function pemeriksaanRanap()
    {
        return $this->hasMany(PemeriksaanRanap::class, 'no_rawat', 'no_rawat');
    }

    /**
     * Get the pemeriksaan ralan that owns the registrasi.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function pemeriksaanRalan()
    {
        return $this->belongsTo(PemeriksaanRalan::class, 'no_rawat', 'no_rawat');
    }

    /**
     * Get the periksa lab that owns the registrasi.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     * */
    public function periksaLab()
    {
        return $this->hasMany(PeriksaLab::class, 'no_rawat', 'no_rawat');
    }

    /**
     * Get the periksa radiologi that owns the registrasi.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     * */
    public function periksaRadiologi()
    {
        return $this->hasMany(PeriksaRadiologi::class, 'no_rawat', 'no_rawat');
    }

    /**
     * Get the detail pemberian obat that owns the registrasi.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     * */
    public function detailPemberianObat()
    {
        return $this->hasMany(DetailPemberianObat::class, 'no_rawat', 'no_rawat');
    }

    /**
     * Get the pasien bayi that owns the registrasi.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     * */
    public function pasienBayi()
    {
        return $this->belongsTo(PasienBayi::class, 'no_rkm_medis', 'no_rkm_medis')
            ->select('no_rkm_medis', 'berat_badan', 'anakke', 'keterangan', 'diagnosa');
    }

    /**
     * Get the diagnosa pasien that owns the registrasi.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     * */
    public function diagnosaPasien()
    {
        return $this->hasMany(DiagnosaPasien::class, 'no_rawat', 'no_rawat');
    }

    /**
     * Get the prosedur pasien that owns the registrasi.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     * */
    public function prosedurPasien()
    {
        return $this->hasMany(ProsedurPasien::class, 'no_rawat', 'no_rawat');
    }

    /**
     * Get the catatan perawatan that owns the registrasi.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     * */
    public function catatanPerawatan()
    {
        return $this->belongsTo(CatatanPerawatan::class, 'no_rawat', 'no_rawat');
    }

    /**
     * Get the bridging sep that owns the registrasi.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     * */
    public function sep()
    {
        return $this->belongsTo(BridgingSep::class, 'no_rawat', 'no_rawat');
    }
}
