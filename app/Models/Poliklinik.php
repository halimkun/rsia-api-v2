<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Poliklinik
 *
 * @property string $kd_poli
 * @property string|null $nm_poli
 * @property float $registrasi
 * @property float $registrasilama
 * @property string $status
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\JadwalPoli> $jadwal_dokter
 * @property-read int|null $jadwal_dokter_count
 * @method static \Illuminate\Database\Eloquent\Builder|Poliklinik newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Poliklinik newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Poliklinik query()
 * @method static \Illuminate\Database\Eloquent\Builder|Poliklinik whereKdPoli($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Poliklinik whereNmPoli($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Poliklinik whereRegistrasi($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Poliklinik whereRegistrasilama($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Poliklinik whereStatus($value)
 * @mixin \Eloquent
 */
class Poliklinik extends Model
{
    use HasFactory;

    protected $table = 'poliklinik';

    protected $primaryKey = 'kd_poli';

    protected $guarded = [];

    public $incrementing = false;
    
    public $timestamps = false;


    public function jadwal_dokter()
    {
        return $this->hasMany(JadwalPoli::class, 'kd_poli', 'kd_poli');
    }
}
