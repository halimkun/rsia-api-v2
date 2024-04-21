<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\SttsKerja
 *
 * @property string $stts
 * @property string $ktg
 * @property int $indek
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Pegawai> $pegawai
 * @property-read int|null $pegawai_count
 * @method static \Illuminate\Database\Eloquent\Builder|SttsKerja newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SttsKerja newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SttsKerja query()
 * @method static \Illuminate\Database\Eloquent\Builder|SttsKerja whereIndek($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SttsKerja whereKtg($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SttsKerja whereStts($value)
 * @mixin \Eloquent
 */
class SttsKerja extends Model
{
    use HasFactory;

    protected $table = "stts_kerja";

    protected $primaryKey = "stts";

    protected $guarded = [];

    protected $keyType = 'string';

    public $incrementing = false;

    public $timestamps = false;

    public function pegawai()
    {
        return $this->hasMany(Pegawai::class, 'stts_kerja', 'stts');
    }
}
