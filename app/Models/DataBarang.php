<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\DataBarang
 *
 * @property string $kode_brng
 * @property string|null $nama_brng
 * @property string $kode_satbesar
 * @property string|null $kode_sat
 * @property string|null $letak_barang
 * @property float $dasar
 * @property float|null $h_beli
 * @property float|null $ralan
 * @property float|null $kelas1
 * @property float|null $kelas2
 * @property float|null $kelas3
 * @property float|null $utama
 * @property float|null $vip
 * @property float|null $vvip
 * @property float|null $beliluar
 * @property float|null $jualbebas
 * @property float|null $karyawan
 * @property float|null $stokminimal
 * @property string|null $kdjns
 * @property float $isi
 * @property float $kapasitas
 * @property string|null $expire
 * @property string $status
 * @property string|null $kode_industri
 * @property string|null $kode_kategori
 * @property string|null $kode_golongan
 * @method static \Illuminate\Database\Eloquent\Builder|DataBarang newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DataBarang newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DataBarang query()
 * @method static \Illuminate\Database\Eloquent\Builder|DataBarang whereBeliluar($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DataBarang whereDasar($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DataBarang whereExpire($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DataBarang whereHBeli($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DataBarang whereIsi($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DataBarang whereJualbebas($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DataBarang whereKapasitas($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DataBarang whereKaryawan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DataBarang whereKdjns($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DataBarang whereKelas1($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DataBarang whereKelas2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DataBarang whereKelas3($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DataBarang whereKodeBrng($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DataBarang whereKodeGolongan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DataBarang whereKodeIndustri($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DataBarang whereKodeKategori($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DataBarang whereKodeSat($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DataBarang whereKodeSatbesar($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DataBarang whereLetakBarang($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DataBarang whereNamaBrng($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DataBarang whereRalan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DataBarang whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DataBarang whereStokminimal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DataBarang whereUtama($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DataBarang whereVip($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DataBarang whereVvip($value)
 * @mixin \Eloquent
 */
class DataBarang extends Model
{
    use HasFactory;

    protected $table = 'databarang';

    protected $primaryKey = 'kode_brng';

    protected $guarded = [];

    public $incrementing = false;

    public $timestamps = false;


    public function satuan()
    {
        return $this->belongsTo(KodeSatuan::class, 'kode_sat', 'kode_sat');
    }
}
