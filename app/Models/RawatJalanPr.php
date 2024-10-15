<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Thiagoprz\CompositeKey\HasCompositeKey;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * App\Models\RawatJalanPr
 *
 * @property string $no_rawat
 * @property string $kd_jenis_prw
 * @property string $nip
 * @property string $tgl_perawatan
 * @property string $jam_rawat
 * @property float $material
 * @property float $bhp
 * @property float $tarif_tindakanpr
 * @property float|null $kso
 * @property float|null $menejemen
 * @property float|null $biaya_rawat
 * @property string|null $stts_bayar
 * @method static \Illuminate\Database\Eloquent\Builder|RawatJalanPr newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RawatJalanPr newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RawatJalanPr query()
 * @method static \Illuminate\Database\Eloquent\Builder|RawatJalanPr whereBhp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RawatJalanPr whereBiayaRawat($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RawatJalanPr whereJamRawat($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RawatJalanPr whereKdJenisPrw($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RawatJalanPr whereKso($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RawatJalanPr whereMaterial($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RawatJalanPr whereMenejemen($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RawatJalanPr whereNip($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RawatJalanPr whereNoRawat($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RawatJalanPr whereSttsBayar($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RawatJalanPr whereTarifTindakanpr($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RawatJalanPr whereTglPerawatan($value)
 * @mixin \Eloquent
 */
class RawatJalanPr extends Model
{
    use HasFactory, HasCompositeKey;

    protected $table = 'rawat_jl_pr';

    protected $primaryKey = ["no_rawat", "kd_jenis_prw", "nip", "tgl_perawatan", "jam_rawat"];

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
