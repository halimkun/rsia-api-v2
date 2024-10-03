<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/**
 * App\Models\Dokter
 *
 * @property string $kd_dokter
 * @property string|null $nm_dokter
 * @property string|null $jk
 * @property string|null $tmp_lahir
 * @property string|null $tgl_lahir
 * @property string|null $gol_drh
 * @property string|null $agama
 * @property string|null $almt_tgl
 * @property string|null $no_telp
 * @property string|null $stts_nikah
 * @property string|null $kd_sps
 * @property string|null $alumni
 * @property string|null $no_ijn_praktek
 * @property string $status
 * @method static \Illuminate\Database\Eloquent\Builder|Dokter newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Dokter newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Dokter query()
 * @method static \Illuminate\Database\Eloquent\Builder|Dokter whereAgama($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Dokter whereAlmtTgl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Dokter whereAlumni($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Dokter whereGolDrh($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Dokter whereJk($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Dokter whereKdDokter($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Dokter whereKdSps($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Dokter whereNmDokter($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Dokter whereNoIjnPraktek($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Dokter whereNoTelp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Dokter whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Dokter whereSttsNikah($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Dokter whereTglLahir($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Dokter whereTmpLahir($value)
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\JadwalPoli> $jadwal
 * @property-read int|null $jadwal_count
 * @property-read \App\Models\Spesialis|null $spesialis
 * @property-read \App\Models\Pegawai $pegawai
 * @property-read \App\Models\SidikJari|null $sidikjari
 * @mixin \Eloquent
 */
class Dokter extends Model
{
    use HasFactory;

    protected $table = 'dokter';

    protected $primaryKey = 'kd_dokter';

    protected $keyType = 'string';
    
    protected $casts = [
        'kd_dokter' => 'string',
    ];
    
    protected $guarded = [];

    public $incrementing = false;

    public $timestamps = false;

    
    public function jadwal()
    {
        return $this->hasMany(JadwalPoli::class, 'kd_dokter', 'kd_dokter');
    }

    public function spesialis()
    {
        return $this->belongsTo(Spesialis::class, 'kd_sps', 'kd_sps');
    }

    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class, 'kd_dokter', 'nik')->select('id', 'nik', 'nama', 'jk' ,'photo');
    }

    public function sidikjari()
    {
        return $this->hasOneThrough(SidikJari::class, Pegawai::class, 'nik', 'id', 'kd_dokter', 'id')
                ->select('sidikjari.id', DB::raw('SHA1(sidikjari) as sidikjari'));
    }
}
