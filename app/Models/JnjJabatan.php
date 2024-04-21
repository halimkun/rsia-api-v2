<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\JnjJabatan
 *
 * @property string $kode
 * @property string $nama
 * @property float $tnj
 * @property int $indek
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Pegawai> $pegawai
 * @property-read int|null $pegawai_count
 * @method static \Illuminate\Database\Eloquent\Builder|JnjJabatan newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|JnjJabatan newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|JnjJabatan query()
 * @method static \Illuminate\Database\Eloquent\Builder|JnjJabatan whereIndek($value)
 * @method static \Illuminate\Database\Eloquent\Builder|JnjJabatan whereKode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|JnjJabatan whereNama($value)
 * @method static \Illuminate\Database\Eloquent\Builder|JnjJabatan whereTnj($value)
 * @mixin \Eloquent
 */
class JnjJabatan extends Model
{
    use HasFactory;

    protected $table = "jnj_jabatan";

    protected $primaryKey = "kode";

    protected $guarded = [];

    protected $keyType = 'string';

    public $incrementing = false;

    public $timestamps = false;

    public function pegawai()
    {
        return $this->hasMany(Pegawai::class, 'jnj_jabatan', 'kode');
    }
}
