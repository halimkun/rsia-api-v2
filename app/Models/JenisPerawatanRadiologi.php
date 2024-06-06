<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\JenisPerawatanRadiologi
 *
 * @property string $kd_jenis_prw
 * @property string|null $nm_perawatan
 * @property float|null $bagian_rs
 * @property float $bhp
 * @property float $tarif_perujuk
 * @property float $tarif_tindakan_dokter
 * @property float|null $tarif_tindakan_petugas
 * @property float|null $kso
 * @property float|null $menejemen
 * @property float|null $total_byr
 * @property string $kd_pj
 * @property string $status
 * @property string $kelas
 * @method static \Illuminate\Database\Eloquent\Builder|JenisPerawatanRadiologi newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|JenisPerawatanRadiologi newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|JenisPerawatanRadiologi query()
 * @method static \Illuminate\Database\Eloquent\Builder|JenisPerawatanRadiologi whereBagianRs($value)
 * @method static \Illuminate\Database\Eloquent\Builder|JenisPerawatanRadiologi whereBhp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|JenisPerawatanRadiologi whereKdJenisPrw($value)
 * @method static \Illuminate\Database\Eloquent\Builder|JenisPerawatanRadiologi whereKdPj($value)
 * @method static \Illuminate\Database\Eloquent\Builder|JenisPerawatanRadiologi whereKelas($value)
 * @method static \Illuminate\Database\Eloquent\Builder|JenisPerawatanRadiologi whereKso($value)
 * @method static \Illuminate\Database\Eloquent\Builder|JenisPerawatanRadiologi whereMenejemen($value)
 * @method static \Illuminate\Database\Eloquent\Builder|JenisPerawatanRadiologi whereNmPerawatan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|JenisPerawatanRadiologi whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|JenisPerawatanRadiologi whereTarifPerujuk($value)
 * @method static \Illuminate\Database\Eloquent\Builder|JenisPerawatanRadiologi whereTarifTindakanDokter($value)
 * @method static \Illuminate\Database\Eloquent\Builder|JenisPerawatanRadiologi whereTarifTindakanPetugas($value)
 * @method static \Illuminate\Database\Eloquent\Builder|JenisPerawatanRadiologi whereTotalByr($value)
 * @mixin \Eloquent
 */
class JenisPerawatanRadiologi extends Model
{
    use HasFactory;

    protected $table = 'jns_perawatan_radiologi';

    protected $primaryKey = 'kd_jenis_prw';

    protected $guarded = [];

    public $timestamps = false;

    public $incrementing = false;
}
