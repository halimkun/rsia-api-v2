<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Thiagoprz\CompositeKey\HasCompositeKey;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * App\Models\RsiaBerkasKomiteKeperawatan
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
 * @method static \Illuminate\Database\Eloquent\Builder|RsiaBerkasKomiteKeperawatan newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RsiaBerkasKomiteKeperawatan newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RsiaBerkasKomiteKeperawatan query()
 * @method static \Illuminate\Database\Eloquent\Builder|RsiaBerkasKomiteKeperawatan whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RsiaBerkasKomiteKeperawatan whereNoSurat($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RsiaBerkasKomiteKeperawatan whereNomor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RsiaBerkasKomiteKeperawatan wherePerihal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RsiaBerkasKomiteKeperawatan wherePj($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RsiaBerkasKomiteKeperawatan wherePrefix($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RsiaBerkasKomiteKeperawatan whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RsiaBerkasKomiteKeperawatan whereTglTerbit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RsiaBerkasKomiteKeperawatan whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class RsiaBerkasKomiteKeperawatan extends Model
{
    use HasFactory, HasCompositeKey;

    protected $table = 'rsia_berkas_komite_keperawatan';

    protected $primaryKey = ['nomor', 'tgl_terbit'];

    protected $guarded = [];


    public function penanggungJawab()
    {
        return $this->belongsTo(Pegawai::class, 'pj', 'nik')->select('nik', 'nama');
    }
}
