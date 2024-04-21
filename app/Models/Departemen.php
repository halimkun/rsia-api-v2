<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Departemen
 *
 * @property string $dep_id
 * @property string $nama
 * @property string|null $kelompok
 * @property string|null $aktif
 * @property string|null $tele_id
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Pegawai> $pegawai
 * @property-read int|null $pegawai_count
 * @method static \Illuminate\Database\Eloquent\Builder|Departemen newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Departemen newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Departemen query()
 * @method static \Illuminate\Database\Eloquent\Builder|Departemen whereAktif($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Departemen whereDepId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Departemen whereKelompok($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Departemen whereNama($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Departemen whereTeleId($value)
 * @mixin \Eloquent
 */
class Departemen extends Model
{
    use HasFactory;

    protected $table = "departemen";

    protected $primaryKey = "dep_id";

    protected $guarded = [];

    protected $keyType = 'string';

    public $incrementing = false;

    public $timestamps = false;
    
    public function pegawai()
    {
        return $this->hasMany(Pegawai::class, 'departemen', 'dep_id');
    }
}
