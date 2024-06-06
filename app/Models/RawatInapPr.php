<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Thiagoprz\CompositeKey\HasCompositeKey;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * App\Models\RawatInapPr
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
 * @method static \Illuminate\Database\Eloquent\Builder|RawatInapPr newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RawatInapPr newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RawatInapPr query()
 * @method static \Illuminate\Database\Eloquent\Builder|RawatInapPr whereBhp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RawatInapPr whereBiayaRawat($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RawatInapPr whereJamRawat($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RawatInapPr whereKdJenisPrw($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RawatInapPr whereKso($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RawatInapPr whereMaterial($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RawatInapPr whereMenejemen($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RawatInapPr whereNip($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RawatInapPr whereNoRawat($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RawatInapPr whereTarifTindakanpr($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RawatInapPr whereTglPerawatan($value)
 * @mixin \Eloquent
 */
class RawatInapPr extends Model
{
    use HasFactory, HasCompositeKey;

    protected $table = 'rawat_inap_pr';

    protected $primaryKey = ["no_rawat", "kd_jenis_prw", "nip", "tgl_perawatan", "jam_rawat"];

    protected $guarded = [];

    protected $casts = [
        'no_rawat' => 'string',
    ];

    public $incrementing = false;

    public $timestamps = false;
}
