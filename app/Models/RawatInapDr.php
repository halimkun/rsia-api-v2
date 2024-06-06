<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Thiagoprz\CompositeKey\HasCompositeKey;

/**
 * App\Models\RawatInapDr
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
 * @method static \Illuminate\Database\Eloquent\Builder|RawatInapDr newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RawatInapDr newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RawatInapDr query()
 * @method static \Illuminate\Database\Eloquent\Builder|RawatInapDr whereBhp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RawatInapDr whereBiayaRawat($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RawatInapDr whereJamRawat($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RawatInapDr whereKdDokter($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RawatInapDr whereKdJenisPrw($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RawatInapDr whereKso($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RawatInapDr whereMaterial($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RawatInapDr whereMenejemen($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RawatInapDr whereNoRawat($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RawatInapDr whereTarifTindakandr($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RawatInapDr whereTglPerawatan($value)
 * @mixin \Eloquent
 */
class RawatInapDr extends Model
{
    use HasFactory, HasCompositeKey;

    protected $table = 'rawat_inap_dr';

    protected $primaryKey = ["no_rawat", "kd_jenis_prw", "kd_dokter", "tgl_perawatan", "jam_rawat"];

    protected $guarded = [];
    
    protected $casts = [
        'no_rawat' => 'string',
    ];

    public $incrementing = false;

    public $timestamps = false;
}
