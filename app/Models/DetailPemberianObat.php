<?php

namespace App\Models;

use Awobaz\Compoships\Compoships;
use Illuminate\Database\Eloquent\Model;
use Thiagoprz\CompositeKey\HasCompositeKey;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * App\Models\DetailPemberianObat
 *
 * @property string $tgl_perawatan
 * @property string $jam
 * @property string $no_rawat
 * @property string $kode_brng
 * @property float|null $h_beli
 * @property float|null $biaya_obat
 * @property float $jml
 * @property float|null $embalase
 * @property float|null $tuslah
 * @property float $total
 * @property string|null $status
 * @property string|null $kd_bangsal
 * @property string $no_batch
 * @property string $no_faktur
 * @property-read \App\Models\DataBarang $obat
 * @property-read \App\Models\RegPeriksa $regPeriksa
 * @method static \Illuminate\Database\Eloquent\Builder|DetailPemberianObat newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DetailPemberianObat newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DetailPemberianObat query()
 * @method static \Illuminate\Database\Eloquent\Builder|DetailPemberianObat whereBiayaObat($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DetailPemberianObat whereEmbalase($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DetailPemberianObat whereHBeli($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DetailPemberianObat whereJam($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DetailPemberianObat whereJml($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DetailPemberianObat whereKdBangsal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DetailPemberianObat whereKodeBrng($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DetailPemberianObat whereNoBatch($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DetailPemberianObat whereNoFaktur($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DetailPemberianObat whereNoRawat($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DetailPemberianObat whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DetailPemberianObat whereTglPerawatan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DetailPemberianObat whereTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DetailPemberianObat whereTuslah($value)
 * @mixin \Eloquent
 */
class DetailPemberianObat extends Model
{
    use HasFactory, HasCompositeKey, Compoships;

    protected $table = 'detail_pemberian_obat';

    protected $primaryKey = ["tgl_perawatan", "jam", "no_rawat", "kode_brng", "no_batch", "no_faktur"];

    protected $guarded = [];

    protected $casts = [
        'no_rawat' => 'string',
    ];

    public $incrementing = false;

    public $timestamps = false;


    public function getTotal()
    {
        return $this->total ?? 0;
    }

    public function regPeriksa()
    {
        return $this->belongsTo(RegPeriksa::class, 'no_rawat', 'no_rawat');
    }

    public function obat()
    {
        return $this->belongsTo(DataBarang::class, 'kode_brng', 'kode_brng')->select('kode_brng', 'nama_brng');
    }

    public function aturanPakai()
    {
        return $this->hasOne(AturanPakai::class, ['tgl_perawatan', 'jam', 'no_rawat', 'kode_brng'], ['tgl_perawatan', 'jam', 'no_rawat', 'kode_brng']);
    }
}
