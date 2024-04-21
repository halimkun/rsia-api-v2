<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Pendidikan
 *
 * @property string $tingkat
 * @property int $indek
 * @property float $gapok1
 * @property float $kenaikan
 * @property int $maksimal
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Pegawai> $pegawai
 * @property-read int|null $pegawai_count
 * @method static \Illuminate\Database\Eloquent\Builder|Pendidikan newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Pendidikan newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Pendidikan query()
 * @method static \Illuminate\Database\Eloquent\Builder|Pendidikan whereGapok1($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pendidikan whereIndek($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pendidikan whereKenaikan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pendidikan whereMaksimal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pendidikan whereTingkat($value)
 * @mixin \Eloquent
 */
class Pendidikan extends Model
{
    use HasFactory;

    protected $table = "pendidikan";

    protected $primaryKey = "tingkat";

    protected $guarded = [];

    protected $keyType = 'string';

    public $incrementing = false;

    public $timestamps = false;

    public function pegawai()
    {
        return $this->hasMany(Pegawai::class, 'pendidikan', 'tingkat');
    }
}
