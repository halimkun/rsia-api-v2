<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * App\Models\RsiaPks
 *
 * @property int $id
 * @property string $no_pks_internal
 * @property string $no_pks_eksternal
 * @property string $judul
 * @property string $tgl_terbit
 * @property string $tanggal_awal
 * @property string|null $tanggal_akhir
 * @property string $berkas
 * @property string $pj
 * @property int $status
 * @property string $created_at
 * @property-read \App\Models\Pegawai $penanggungJawab
 * @method static \Illuminate\Database\Eloquent\Builder|RsiaPks newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RsiaPks newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RsiaPks query()
 * @method static \Illuminate\Database\Eloquent\Builder|RsiaPks whereBerkas($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RsiaPks whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RsiaPks whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RsiaPks whereJudul($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RsiaPks whereNoPksEksternal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RsiaPks whereNoPksInternal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RsiaPks wherePj($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RsiaPks whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RsiaPks whereTanggalAkhir($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RsiaPks whereTanggalAwal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RsiaPks whereTglTerbit($value)
 * @mixin \Eloquent
 */
class RsiaPks extends Model
{
    use HasFactory;

    protected $table = 'rsia_pks';

    protected $primaryKey = 'id';

    protected $guarded = ['id'];

    public $timestamps = false;


    public function penanggungJawab()
    {
        return $this->belongsTo(Pegawai::class, 'pj', 'nik')->select('nik', 'nama');
    }
}
