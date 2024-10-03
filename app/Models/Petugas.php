<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Petugas
 *
 * @property string $nip
 * @property string|null $nama
 * @property string|null $jk
 * @property string|null $tmp_lahir
 * @property string|null $tgl_lahir
 * @property string|null $gol_darah
 * @property string|null $agama
 * @property string|null $stts_nikah
 * @property string|null $alamat
 * @property string|null $kd_jbtn
 * @property string|null $no_telp
 * @property string|null $status
 * @method static \Illuminate\Database\Eloquent\Builder|Petugas newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Petugas newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Petugas query()
 * @method static \Illuminate\Database\Eloquent\Builder|Petugas whereAgama($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Petugas whereAlamat($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Petugas whereGolDarah($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Petugas whereJk($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Petugas whereKdJbtn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Petugas whereNama($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Petugas whereNip($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Petugas whereNoTelp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Petugas whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Petugas whereSttsNikah($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Petugas whereTglLahir($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Petugas whereTmpLahir($value)
 * 
 * @property-read \App\Models\Pegawai|null $pegawai
 * @property-read \App\Models\SidikJari|null $sidikjari
 * 
 * @mixin \Eloquent
 */
class Petugas extends Model
{
    use HasFactory;

    protected $table = 'petugas';

    protected $primaryKey = 'nip';

    protected $guarded = [];

    public $timestamps = false;

    public $incrementing = false;

    public $keyType = 'string';


    /**
     * Get the pegawai that owns the Petugas
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class, 'nip', 'nik');
    }

    /**
     * Get the sidikjari associated with the Petugas
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOneThrough
     */
    public function sidikjari()
    {
        return $this->hasOneThrough(SidikJari::class, Pegawai::class, 'nik', 'id', 'nip', 'id')
            ->select('sidikjari.id', \Illuminate\Support\Facades\DB::raw('SHA1(sidikjari) as sidikjari'));
    }
}
