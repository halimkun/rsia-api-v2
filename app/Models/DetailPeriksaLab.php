<?php

namespace App\Models;

use Awobaz\Compoships\Compoships;
use Illuminate\Database\Eloquent\Model;
use Thiagoprz\CompositeKey\HasCompositeKey;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * App\Models\DetailPeriksaLab
 *
 * @property string $no_rawat
 * @property string $kd_jenis_prw
 * @property string $tgl_periksa
 * @property string $jam
 * @property int $id_template
 * @property string $nilai
 * @property string $nilai_rujukan
 * @property string $keterangan
 * @property float $bagian_rs
 * @property float $bhp
 * @property float $bagian_perujuk
 * @property float $bagian_dokter
 * @property float $bagian_laborat
 * @property float|null $kso
 * @property float|null $menejemen
 * @property float $biaya_item
 * @property-read \App\Models\TemplateLaboratorium $template
 * @method static \Illuminate\Database\Eloquent\Builder|DetailPeriksaLab newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DetailPeriksaLab newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DetailPeriksaLab query()
 * @method static \Illuminate\Database\Eloquent\Builder|DetailPeriksaLab whereBagianDokter($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DetailPeriksaLab whereBagianLaborat($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DetailPeriksaLab whereBagianPerujuk($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DetailPeriksaLab whereBagianRs($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DetailPeriksaLab whereBhp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DetailPeriksaLab whereBiayaItem($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DetailPeriksaLab whereIdTemplate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DetailPeriksaLab whereJam($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DetailPeriksaLab whereKdJenisPrw($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DetailPeriksaLab whereKeterangan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DetailPeriksaLab whereKso($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DetailPeriksaLab whereMenejemen($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DetailPeriksaLab whereNilai($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DetailPeriksaLab whereNilaiRujukan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DetailPeriksaLab whereNoRawat($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DetailPeriksaLab whereTglPeriksa($value)
 * @mixin \Eloquent
 */
class DetailPeriksaLab extends Model
{
    use HasFactory, HasCompositeKey, Compoships;

    protected $table = 'detail_periksa_lab';

    protected $primaryKey = ["no_rawat", "kd_jenis_prw", "tgl_periksa", "jam", "id_template"];

    protected $guarded = [];

    protected $casts = [
        'no_rawat' => 'string',
    ];

    public $incrementing = false;

    public $timestamps = false;


    public function template()
    {
        return $this->belongsTo(TemplateLaboratorium::class, 'id_template', 'id_template')
            ->select(["kd_jenis_prw", "id_template", "Pemeriksaan", "satuan", "nilai_rujukan_ld", "nilai_rujukan_la", "nilai_rujukan_pd", "nilai_rujukan_pa"]);
    }
}
