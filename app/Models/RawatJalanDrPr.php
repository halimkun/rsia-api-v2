<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Thiagoprz\CompositeKey\HasCompositeKey;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * App\Models\RawatJalanDrPr
 *
 * @property string $no_rawat
 * @property string $kd_jenis_prw
 * @property string $kd_dokter
 * @property string $nip
 * @property string $tgl_perawatan
 * @property string $jam_rawat
 * @property float|null $material
 * @property float $bhp
 * @property float|null $tarif_tindakandr
 * @property float|null $tarif_tindakanpr
 * @property float|null $kso
 * @property float|null $menejemen
 * @property float|null $biaya_rawat
 * @property string|null $stts_bayar
 * @method static \Illuminate\Database\Eloquent\Builder|RawatJalanDrPr newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RawatJalanDrPr newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RawatJalanDrPr query()
 * @method static \Illuminate\Database\Eloquent\Builder|RawatJalanDrPr whereBhp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RawatJalanDrPr whereBiayaRawat($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RawatJalanDrPr whereJamRawat($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RawatJalanDrPr whereKdDokter($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RawatJalanDrPr whereKdJenisPrw($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RawatJalanDrPr whereKso($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RawatJalanDrPr whereMaterial($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RawatJalanDrPr whereMenejemen($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RawatJalanDrPr whereNip($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RawatJalanDrPr whereNoRawat($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RawatJalanDrPr whereSttsBayar($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RawatJalanDrPr whereTarifTindakandr($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RawatJalanDrPr whereTarifTindakanpr($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RawatJalanDrPr whereTglPerawatan($value)
 * @mixin \Eloquent
 */
class RawatJalanDrPr extends Model
{
    use HasFactory, HasCompositeKey;

    protected $table = 'rawat_jl_drpr';

    protected $primaryKey = ["no_rawat", "kd_jenis_prw", "kd_dokter", "nip", "tgl_perawatan", "jam_rawat"];

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
}
