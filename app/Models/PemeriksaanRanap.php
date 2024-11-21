<?php

namespace App\Models;

use Awobaz\Compoships\Compoships;
use Illuminate\Database\Eloquent\Model;
use Thiagoprz\CompositeKey\HasCompositeKey;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * App\Models\PemeriksaanRanap
 *
 * @property string $no_rawat
 * @property string $tgl_perawatan
 * @property string $jam_rawat
 * @property string|null $suhu_tubuh
 * @property string $tensi
 * @property string|null $nadi
 * @property string|null $respirasi
 * @property string|null $tinggi
 * @property string|null $berat
 * @property string $spo2
 * @property string|null $gcs
 * @property string $kesadaran
 * @property string|null $keluhan
 * @property string|null $pemeriksaan
 * @property string|null $alergi
 * @property string $penilaian
 * @property string $rtl
 * @property string $instruksi
 * @property string $evaluasi
 * @property string $nip
 * @property-read \App\Models\Petugas $petugas
 * @property-read \App\Models\RegPeriksa $regPeriksa
 * @method static \Illuminate\Database\Eloquent\Builder|PemeriksaanRanap newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PemeriksaanRanap newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PemeriksaanRanap query()
 * @method static \Illuminate\Database\Eloquent\Builder|PemeriksaanRanap whereAlergi($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PemeriksaanRanap whereBerat($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PemeriksaanRanap whereEvaluasi($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PemeriksaanRanap whereGcs($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PemeriksaanRanap whereInstruksi($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PemeriksaanRanap whereJamRawat($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PemeriksaanRanap whereKeluhan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PemeriksaanRanap whereKesadaran($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PemeriksaanRanap whereNadi($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PemeriksaanRanap whereNip($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PemeriksaanRanap whereNoRawat($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PemeriksaanRanap wherePemeriksaan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PemeriksaanRanap wherePenilaian($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PemeriksaanRanap whereRespirasi($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PemeriksaanRanap whereRtl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PemeriksaanRanap whereSpo2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PemeriksaanRanap whereSuhuTubuh($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PemeriksaanRanap whereTensi($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PemeriksaanRanap whereTglPerawatan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PemeriksaanRanap whereTinggi($value)
 * @mixin \Eloquent
 */
class PemeriksaanRanap extends Model
{
    use HasFactory, HasCompositeKey, Compoships;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'pemeriksaan_ranap';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = ['no_rawat', 'tgl_perawatan', 'jam_rawat'];

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'no_rawat' => 'string',
        'no_resep' => 'string',
    ];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;


    /**
     * Get the regPeriksa that owns the PemeriksaanRanap
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function regPeriksa()
    {
        return $this->belongsTo(RegPeriksa::class, 'no_rawat', 'no_rawat')->select('no_rawat', 'no_rkm_medis', 'tgl_registrasi', 'jam_reg', 'kd_poli', 'kd_dokter')->with(['dokter', 'poliklinik', 'sep' => function ($query) {
            $query->select('no_sep', 'no_rawat');
        }]);
    }

    /**
     * Get the petugas that owns the PemeriksaanRanap
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function petugas()
    {
        return $this->belongsTo(Petugas::class, 'nip', 'nip')->select('nip', 'nama');
    }

    /**
     * Get the pemeriksaanKlaim that owns the PemeriksaanRanap
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function pemeriksaanKlaim()
    {
        return $this->hasOne(PemeriksaanRanapKlaim::class, ['no_rawat', 'tgl_perawatan', 'jam_rawat'], ['no_rawat', 'tgl_perawatan', 'jam_rawat'])->select('no_rawat', 'tgl_perawatan', 'jam_rawat');
    }
}
