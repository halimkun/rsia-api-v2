<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\TemplateLaboratorium
 *
 * @property string $kd_jenis_prw
 * @property int $id_template
 * @property string $Pemeriksaan
 * @property string $satuan
 * @property string $nilai_rujukan_ld
 * @property string $nilai_rujukan_la
 * @property string $nilai_rujukan_pd
 * @property string $nilai_rujukan_pa
 * @property float $bagian_rs
 * @property float $bhp
 * @property float $bagian_perujuk
 * @property float $bagian_dokter
 * @property float $bagian_laborat
 * @property float|null $kso
 * @property float|null $menejemen
 * @property float $biaya_item
 * @property int|null $urut
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\DetailPeriksaLab> $detailPeriksaLab
 * @property-read int|null $detail_periksa_lab_count
 * @method static \Illuminate\Database\Eloquent\Builder|TemplateLaboratorium newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TemplateLaboratorium newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TemplateLaboratorium query()
 * @method static \Illuminate\Database\Eloquent\Builder|TemplateLaboratorium whereBagianDokter($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TemplateLaboratorium whereBagianLaborat($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TemplateLaboratorium whereBagianPerujuk($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TemplateLaboratorium whereBagianRs($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TemplateLaboratorium whereBhp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TemplateLaboratorium whereBiayaItem($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TemplateLaboratorium whereIdTemplate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TemplateLaboratorium whereKdJenisPrw($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TemplateLaboratorium whereKso($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TemplateLaboratorium whereMenejemen($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TemplateLaboratorium whereNilaiRujukanLa($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TemplateLaboratorium whereNilaiRujukanLd($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TemplateLaboratorium whereNilaiRujukanPa($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TemplateLaboratorium whereNilaiRujukanPd($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TemplateLaboratorium wherePemeriksaan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TemplateLaboratorium whereSatuan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TemplateLaboratorium whereUrut($value)
 * @mixin \Eloquent
 */
class TemplateLaboratorium extends Model
{
    use HasFactory;

    protected $table = 'template_laboratorium';

    protected $primaryKey = 'id_template';

    protected $guarded = ['id_template'];

    protected $casts = [
        'no_rawat' => 'string',
    ];

    public $timestamps = false;


    public function detailPeriksaLab()
    {
        return $this->hasMany(DetailPeriksaLab::class, 'id_template', 'id_template');
    }
}
