<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Thiagoprz\CompositeKey\HasCompositeKey;

/**
 * App\Models\RsiaBerkasKomitePmkp
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
 * @method static \Illuminate\Database\Eloquent\Builder|RsiaBerkasKomitePmkp newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RsiaBerkasKomitePmkp newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RsiaBerkasKomitePmkp query()
 * @method static \Illuminate\Database\Eloquent\Builder|RsiaBerkasKomitePmkp whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RsiaBerkasKomitePmkp whereNoSurat($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RsiaBerkasKomitePmkp whereNomor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RsiaBerkasKomitePmkp wherePerihal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RsiaBerkasKomitePmkp wherePj($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RsiaBerkasKomitePmkp wherePrefix($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RsiaBerkasKomitePmkp whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RsiaBerkasKomitePmkp whereTglTerbit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RsiaBerkasKomitePmkp whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class RsiaBerkasKomitePmkp extends Model
{
    use HasFactory, HasCompositeKey;

    protected $table = 'rsia_berkas_komite_pmkp';

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
