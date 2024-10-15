<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Thiagoprz\CompositeKey\HasCompositeKey;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * App\Models\RawatInapDrPr
 *
 * @property string $no_rawat
 * @property string $kd_jenis_prw
 * @property string $kd_dokter
 * @property string $nip
 * @property string $tgl_perawatan
 * @property string $jam_rawat
 * @property float $material
 * @property float $bhp
 * @property float|null $tarif_tindakandr
 * @property float|null $tarif_tindakanpr
 * @property float|null $kso
 * @property float|null $menejemen
 * @property float|null $biaya_rawat
 * @method static \Illuminate\Database\Eloquent\Builder|RawatInapDrPr newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RawatInapDrPr newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RawatInapDrPr query()
 * @method static \Illuminate\Database\Eloquent\Builder|RawatInapDrPr whereBhp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RawatInapDrPr whereBiayaRawat($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RawatInapDrPr whereJamRawat($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RawatInapDrPr whereKdDokter($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RawatInapDrPr whereKdJenisPrw($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RawatInapDrPr whereKso($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RawatInapDrPr whereMaterial($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RawatInapDrPr whereMenejemen($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RawatInapDrPr whereNip($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RawatInapDrPr whereNoRawat($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RawatInapDrPr whereTarifTindakandr($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RawatInapDrPr whereTarifTindakanpr($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RawatInapDrPr whereTglPerawatan($value)
 * @mixin \Eloquent
 */
class RawatInapDrPr extends Model
{
    use HasFactory, HasCompositeKey;

    protected $table = 'rawat_inap_drpr';

    protected $primaryKey = ["no_rawat", "kd_jenis_prw", "kd_dokter", "nip", "tgl_perawatan", "jam_rawat",];

    protected $guarded = [];

    protected $casts = [
        'no_rawat' => 'string',
    ];

    public $incrementing = false;

    public $timestamps = false;

    public function jenisPerawatan()
    {
        return $this->belongsTo(JenisPerawatanInap::class, 'kd_jenis_prw', 'kd_jenis_prw');
    }
}
