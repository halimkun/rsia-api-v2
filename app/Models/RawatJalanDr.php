<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Thiagoprz\CompositeKey\HasCompositeKey;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * App\Models\RawatJalanDr
 *
 * @property string $no_rawat
 * @property string $kd_jenis_prw
 * @property string $kd_dokter
 * @property string $tgl_perawatan
 * @property string $jam_rawat
 * @property float $material
 * @property float $bhp
 * @property float $tarif_tindakandr
 * @property float|null $kso
 * @property float|null $menejemen
 * @property float|null $biaya_rawat
 * @property string|null $stts_bayar
 * @method static \Illuminate\Database\Eloquent\Builder|RawatJalanDr newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RawatJalanDr newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RawatJalanDr query()
 * @method static \Illuminate\Database\Eloquent\Builder|RawatJalanDr whereBhp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RawatJalanDr whereBiayaRawat($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RawatJalanDr whereJamRawat($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RawatJalanDr whereKdDokter($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RawatJalanDr whereKdJenisPrw($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RawatJalanDr whereKso($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RawatJalanDr whereMaterial($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RawatJalanDr whereMenejemen($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RawatJalanDr whereNoRawat($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RawatJalanDr whereSttsBayar($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RawatJalanDr whereTarifTindakandr($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RawatJalanDr whereTglPerawatan($value)
 * @mixin \Eloquent
 */
class RawatJalanDr extends Model
{
    use HasFactory, HasCompositeKey;

    protected $table = 'rawat_jl_dr';

    protected $primaryKey = ["no_rawat", "kd_jenis_prw", "kd_dokter", "tgl_perawatan", "jam_rawat"];

    protected $guarded = [];

    protected $casts = [
        'no_rawat' => 'string',
    ];

    public $incrementing = false;

    public $timestamps = false;

    public function jenisPerawatan()
    {
        return $this->belongsTo(JenisPerawatan::class, 'kd_jenis_prw', 'kd_jenis_prw');
    }

    public function dokter()
    {
        return $this->belongsTo(Dokter::class, 'kd_dokter', 'kd_dokter')->select('kd_dokter', 'nm_dokter', 'kd_sps');
    }
}
