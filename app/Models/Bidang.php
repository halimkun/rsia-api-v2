<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Bidang
 *
 * @property string $nama
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Pegawai> $pegawai
 * @property-read int|null $pegawai_count
 * @method static \Illuminate\Database\Eloquent\Builder|Bidang newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Bidang newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Bidang query()
 * @method static \Illuminate\Database\Eloquent\Builder|Bidang whereNama($value)
 * @mixin \Eloquent
 */
class Bidang extends Model
{
    use HasFactory;

    protected $table = "bidang";

    protected $primaryKey = "nama";

    protected $guarded = [];

    protected $keyType = 'string';

    public $incrementing = false;

    public $timestamps = false;

    public function pegawai()
    {
        return $this->hasMany(Pegawai::class, 'bidang', 'nama');
    }
}
