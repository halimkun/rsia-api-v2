<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Thiagoprz\CompositeKey\HasCompositeKey;

/**
 * App\Models\PemeriksaanRalan
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
 * @property string|null $lingkar_perut
 * @property string $rtl
 * @property string $penilaian
 * @property string $instruksi
 * @property string $evaluasi
 * @property string $nip
 * @property-read \App\Models\Petugas $petugas
 * @property-read \App\Models\RegPeriksa $regPeriksa
 * @method static \Illuminate\Database\Eloquent\Builder|PemeriksaanRalan newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PemeriksaanRalan newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PemeriksaanRalan query()
 * @method static \Illuminate\Database\Eloquent\Builder|PemeriksaanRalan whereAlergi($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PemeriksaanRalan whereBerat($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PemeriksaanRalan whereEvaluasi($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PemeriksaanRalan whereGcs($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PemeriksaanRalan whereInstruksi($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PemeriksaanRalan whereJamRawat($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PemeriksaanRalan whereKeluhan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PemeriksaanRalan whereKesadaran($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PemeriksaanRalan whereLingkarPerut($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PemeriksaanRalan whereNadi($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PemeriksaanRalan whereNip($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PemeriksaanRalan whereNoRawat($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PemeriksaanRalan wherePemeriksaan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PemeriksaanRalan wherePenilaian($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PemeriksaanRalan whereRespirasi($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PemeriksaanRalan whereRtl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PemeriksaanRalan whereSpo2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PemeriksaanRalan whereSuhuTubuh($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PemeriksaanRalan whereTensi($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PemeriksaanRalan whereTglPerawatan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PemeriksaanRalan whereTinggi($value)
 * @mixin \Eloquent
 */
class PemeriksaanRalan extends Model
{
    use HasFactory, HasCompositeKey;

    protected $table = 'pemeriksaan_ralan';

    protected $primaryKey = ['no_rawat', 'tgl_perawatan', 'jam_rawat'];

    protected $guarded = [];

    protected $casts = [
        'no_rawat' => 'string',
        'no_resep' => 'string',
    ];

    public $timestamps = false;

    public $incrementing = false;


    public function regPeriksa()
    {
        return $this->belongsTo(RegPeriksa::class, 'no_rawat', 'no_rawat');
    }

    public function petugas()
    {
        return $this->belongsTo(Petugas::class, 'nip', 'nip')->select('nip', 'nama');
    }
}
