<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Thiagoprz\CompositeKey\HasCompositeKey;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * App\Models\KamarInap
 *
 * @property string $no_rawat
 * @property string $kd_kamar
 * @property float|null $trf_kamar
 * @property string|null $diagnosa_awal
 * @property string|null $diagnosa_akhir
 * @property string $tgl_masuk
 * @property string $jam_masuk
 * @property string|null $tgl_keluar
 * @property string|null $jam_keluar
 * @property float|null $lama
 * @property float|null $ttl_biaya
 * @property string $stts_pulang
 * @property-read \Illuminate\Database\Eloquent\Collection<int, KamarInap> $lamaInap
 * @property-read int|null $lama_inap_count
 * @property-read \App\Models\Pasien|null $pasien
 * @property-read \App\Models\RegPeriksa $regPeriksa
 * @property-read \App\Models\RegPeriksa $regPeriksaSimple
 * @property-read \App\Models\BridgingSep|null $sep
 * @property-read \App\Models\BridgingSep|null $sepSimple
 * @method static \Illuminate\Database\Eloquent\Builder|KamarInap newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|KamarInap newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|KamarInap query()
 * @method static \Illuminate\Database\Eloquent\Builder|KamarInap whereDiagnosaAkhir($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KamarInap whereDiagnosaAwal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KamarInap whereJamKeluar($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KamarInap whereJamMasuk($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KamarInap whereKdKamar($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KamarInap whereLama($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KamarInap whereNoRawat($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KamarInap whereSttsPulang($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KamarInap whereTglKeluar($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KamarInap whereTglMasuk($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KamarInap whereTrfKamar($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KamarInap whereTtlBiaya($value)
 * @mixin \Eloquent
 */
class KamarInap extends Model
{
    use HasFactory, HasCompositeKey;

    protected $table = 'kamar_inap';

    protected $primaryKey = ["no_rawat", "tgl_masuk", "jam_masuk"];

    protected $guarded = [];

    protected $casts = [
        'no_rawat' => 'string',
    ];

    public $incrementing = false;

    public $timestamps = false;

    /**
     * Scope a query to only include records that have berkas perawatan.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $kode
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeHasBerkasPerawatan($query, $kode = '009')
    {
        return $query->whereHas('sepSimple.berkasPerawatan', function ($query) use ($kode) {
            $query->where('kode', $kode);
        });
    }

    /**
     * Scope a query to only include records that do not have berkas perawatan.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $kode
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeNotHasBerkasPerawatan($query, $kode = '009')
    {
        return $query->whereDoesntHave('sepSimple.berkasPerawatan', function ($query) use ($kode) {
            $query->where('kode', $kode);
        });
    }

    /**
     * Scope a query to only include records that have status klaim.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeNotHasStatusKlaim($query)
    {
        return $query->whereDoesntHave('sepSimple.status_klaim');
    }


    /**
     * relation to reg periksa
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function regPeriksa()
    {
        return $this->belongsTo(RegPeriksa::class, 'no_rawat', 'no_rawat');
    }

    /**
     * relation to lama inap
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function lamaInap()
    {
        return $this->hasMany(KamarInap::class, 'no_rawat', 'no_rawat')->select('no_rawat', 'lama');
    }

    /**
     * relation to reg periksa but only select some columns
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function regPeriksaSimple()
    {
        return $this->belongsTo(RegPeriksa::class, 'no_rawat', 'no_rawat')->select('no_rawat', 'kd_pj', 'no_rkm_medis', 'kd_dokter', 'tgl_registrasi', 'jam_reg', 'kd_poli', 'status_lanjut');
    }

    /**
     * relation to pasien
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function pasien()
    {
        return $this->hasOneThrough(Pasien::class, RegPeriksa::class, 'no_rawat', 'no_rkm_medis', 'no_rawat', 'no_rkm_medis')
            ->select('pasien.no_rkm_medis', 'pasien.nm_pasien', 'pasien.jk', 'pasien.tmp_lahir', 'pasien.tgl_lahir');
    }

    /**
     * relation to bridging sep
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function sep()
    {
        return $this->hasOne(BridgingSep::class, 'no_rawat', 'no_rawat');
    }

    /**
     * relation to bridging sep but only select some columns
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function sepSimple()
    {
        return $this->hasOne(BridgingSep::class, 'no_rawat', 'no_rawat')->select('no_sep', 'no_rawat', 'diagawal', 'klsrawat', 'klsnaik', 'nomr', 'no_kartu', 'nmdpdjp');
    }

    /**
     * relation to kamar
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function kamar()
    {
        return $this->belongsTo(\App\Models\Kamar::class, 'kd_kamar', 'kd_kamar')->select("kd_kamar", 'kd_bangsal', 'status');
    }

    /**
     * relation to poliklinik
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOneThrough
     */
    public function poliklinik()
    {
        return $this->hasOneThrough(Poliklinik::class, RegPeriksa::class, 'no_rawat', 'kd_poli', 'no_rawat', 'kd_poli');
    }

    /**
     * relation to dokter
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOneThrough
     */
    public function dokter()
    {
        return $this->hasOneThrough(Dokter::class, RegPeriksa::class, 'no_rawat', 'kd_dokter', 'no_rawat', 'kd_dokter');
    }
}
