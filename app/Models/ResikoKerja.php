<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\ResikoKerja
 *
 * @property string $kode_resiko
 * @property string|null $nama_resiko
 * @property int|null $indek
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Pegawai> $pegawai
 * @property-read int|null $pegawai_count
 * @method static \Illuminate\Database\Eloquent\Builder|ResikoKerja newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ResikoKerja newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ResikoKerja query()
 * @method static \Illuminate\Database\Eloquent\Builder|ResikoKerja whereIndek($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ResikoKerja whereKodeResiko($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ResikoKerja whereNamaResiko($value)
 * @mixin \Eloquent
 */
class ResikoKerja extends Model
{
    use HasFactory;

    protected $table = "resiko_kerja";

    protected $primaryKey = "kode_resiko";

    protected $guarded = [];

    protected $keyType = 'string';

    public $incrementing = false;

    public $timestamps = false;

    public function pegawai()
    {
        return $this->hasMany(Pegawai::class, 'kode_resiko', 'kode_resiko');
    }
}
