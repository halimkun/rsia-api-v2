<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Thiagoprz\CompositeKey\HasCompositeKey;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * App\Models\RsiaBerkasKomiteMedis
 *
 * @property int $nomor
 * @property string|null $no_surat
 * @property string $prefix
 * @property string $pj
 * @property string $perihal
 * @property string $tgl_terbit
 * @property int $status
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property-read \App\Models\Pegawai|null $penanggungJawab
 * @method static \Illuminate\Database\Eloquent\Builder|RsiaBerkasKomiteMedis newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RsiaBerkasKomiteMedis newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RsiaBerkasKomiteMedis query()
 * @method static \Illuminate\Database\Eloquent\Builder|RsiaBerkasKomiteMedis whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RsiaBerkasKomiteMedis whereNoSurat($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RsiaBerkasKomiteMedis whereNomor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RsiaBerkasKomiteMedis wherePerihal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RsiaBerkasKomiteMedis wherePj($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RsiaBerkasKomiteMedis wherePrefix($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RsiaBerkasKomiteMedis whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RsiaBerkasKomiteMedis whereTglTerbit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RsiaBerkasKomiteMedis whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class RsiaBerkasKomiteMedis extends Model
{
    use HasFactory, HasCompositeKey;

    protected $table = 'rsia_berkas_komite_medis';

    protected $primaryKey = ['nomor', 'tgl_terbit'];

    protected $guarded = [];


    public function penanggungJawab()
    {
        return $this->belongsTo(Pegawai::class, 'pj', 'nik');
    }

    public function penanggungJawabSimple()
    {
        return $this->belongsTo(Pegawai::class, 'pj', 'nik')->select('nik', 'nama');
    }
}
