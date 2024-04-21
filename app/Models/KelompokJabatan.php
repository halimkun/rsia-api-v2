<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\KelompokJabatan
 *
 * @property string $kode_kelompok
 * @property string|null $nama_kelompok
 * @property int|null $indek
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Pegawai> $pegawai
 * @property-read int|null $pegawai_count
 * @method static \Illuminate\Database\Eloquent\Builder|KelompokJabatan newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|KelompokJabatan newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|KelompokJabatan query()
 * @method static \Illuminate\Database\Eloquent\Builder|KelompokJabatan whereIndek($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KelompokJabatan whereKodeKelompok($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KelompokJabatan whereNamaKelompok($value)
 * @mixin \Eloquent
 */
class KelompokJabatan extends Model
{
    use HasFactory;

    protected $table = "kelompok_jabatan";

    protected $primaryKey = "kode_kelompok";

    protected $guarded = [];

    protected $keyType = 'string';

    public $incrementing = false;

    public $timestamps = false;

    public function pegawai()
    {
        return $this->hasMany(Pegawai::class, 'kode_kelompok', 'kode_kelompok');
    }
}
