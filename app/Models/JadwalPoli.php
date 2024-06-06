<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Thiagoprz\CompositeKey\HasCompositeKey;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * App\Models\JadwalPoli
 *
 * @property string $kd_dokter
 * @property string $hari_kerja
 * @property string $jam_mulai
 * @property string|null $jam_selesai
 * @property string|null $kd_poli
 * @property int|null $kuota
 * @property-read \App\Models\Dokter $dokter
 * @property-read \App\Models\Poliklinik|null $poliklinik
 * @method static \Illuminate\Database\Eloquent\Builder|JadwalPoli newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|JadwalPoli newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|JadwalPoli query()
 * @method static \Illuminate\Database\Eloquent\Builder|JadwalPoli whereHariKerja($value)
 * @method static \Illuminate\Database\Eloquent\Builder|JadwalPoli whereJamMulai($value)
 * @method static \Illuminate\Database\Eloquent\Builder|JadwalPoli whereJamSelesai($value)
 * @method static \Illuminate\Database\Eloquent\Builder|JadwalPoli whereKdDokter($value)
 * @method static \Illuminate\Database\Eloquent\Builder|JadwalPoli whereKdPoli($value)
 * @method static \Illuminate\Database\Eloquent\Builder|JadwalPoli whereKuota($value)
 * @mixin \Eloquent
 */
class JadwalPoli extends Model
{
    use HasFactory, HasCompositeKey;

    protected $table = 'jadwal';

    protected $primaryKey = ['kd_dokter', 'hari_kerja', 'jam_mulai'];

    // protected $guarded = [];

    public $timestamps = false;

    public $incrementing = false;

    
    public function poliklinik()
    {
        return $this->belongsTo(Poliklinik::class, 'kd_poli', 'kd_poli');
    }

    public function dokter()
    {
        return $this->belongsTo(Dokter::class, 'kd_dokter', 'kd_dokter');
    }
}
