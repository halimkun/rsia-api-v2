<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\JenisPerawatanLab
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
 * @property string $kategori
 * @method static \Illuminate\Database\Eloquent\Builder|JenisPerawatanLab newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|JenisPerawatanLab newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|JenisPerawatanLab query()
 * @method static \Illuminate\Database\Eloquent\Builder|JenisPerawatanLab whereBagianRs($value)
 * @method static \Illuminate\Database\Eloquent\Builder|JenisPerawatanLab whereBhp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|JenisPerawatanLab whereKategori($value)
 * @method static \Illuminate\Database\Eloquent\Builder|JenisPerawatanLab whereKdJenisPrw($value)
 * @method static \Illuminate\Database\Eloquent\Builder|JenisPerawatanLab whereKdPj($value)
 * @method static \Illuminate\Database\Eloquent\Builder|JenisPerawatanLab whereKelas($value)
 * @method static \Illuminate\Database\Eloquent\Builder|JenisPerawatanLab whereKso($value)
 * @method static \Illuminate\Database\Eloquent\Builder|JenisPerawatanLab whereMenejemen($value)
 * @method static \Illuminate\Database\Eloquent\Builder|JenisPerawatanLab whereNmPerawatan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|JenisPerawatanLab whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|JenisPerawatanLab whereTarifPerujuk($value)
 * @method static \Illuminate\Database\Eloquent\Builder|JenisPerawatanLab whereTarifTindakanDokter($value)
 * @method static \Illuminate\Database\Eloquent\Builder|JenisPerawatanLab whereTarifTindakanPetugas($value)
 * @method static \Illuminate\Database\Eloquent\Builder|JenisPerawatanLab whereTotalByr($value)
 * @mixin \Eloquent
 */
class JenisPerawatanLab extends Model
{
    use HasFactory;

    protected $table = 'jns_perawatan_lab';

    protected $primaryKey = 'kd_jenis_prw';

    protected $guarded = [];

    public $timestamps = false;

    public $incrementing = false;
}
