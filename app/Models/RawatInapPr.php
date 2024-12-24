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

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'rawat_inap_pr';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = ["no_rawat", "kd_jenis_prw", "nip", "tgl_perawatan", "jam_rawat"];

    /**
     * The attributes that are mass assignable.
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
    ];

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * Get the rawat inap that owns the rawat inap pr.
     */
    public function jenisPerawatan()
    {
        return $this->belongsTo(JenisPerawatanInap::class, 'kd_jenis_prw', 'kd_jenis_prw');
    }
}
