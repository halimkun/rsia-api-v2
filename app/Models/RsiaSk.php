<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Thiagoprz\CompositeKey\HasCompositeKey;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * App\Models\RsiaSk
 *
 * @property int $nomor
 * @property string $prefix
 * @property string $jenis A = SK Dokumen
 * B = Pengangkatan Jabatan
 * @property string $judul
 * @property string $pj
 * @property string $tgl_terbit
 * @property string $berkas
 * @property string $status
 * @property string $created_at
 * @property-read \App\Models\Pegawai $penanggungJawab
 * @method static \Illuminate\Database\Eloquent\Builder|RsiaSk newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RsiaSk newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RsiaSk query()
 * @method static \Illuminate\Database\Eloquent\Builder|RsiaSk whereBerkas($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RsiaSk whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RsiaSk whereJenis($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RsiaSk whereJudul($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RsiaSk whereNomor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RsiaSk wherePj($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RsiaSk wherePrefix($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RsiaSk whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RsiaSk whereTglTerbit($value)
 * @mixin \Eloquent
 */
class RsiaSk extends Model
{
    use HasFactory, HasCompositeKey;

    protected $table = 'rsia_sk';

    protected $primaryKey = ['nomor', 'jenis', 'tgl_terbit'];

    protected $guarded = [];

    public $timestamps = false;


    public function penanggungJawab()
    {
        return $this->belongsTo(Pegawai::class, 'pj', 'nik')->select('nik', 'nama');
    }
}
