<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Thiagoprz\CompositeKey\HasCompositeKey;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * App\Models\RsiaBerkasIht
 *
 * @property int $nomor
 * @property string $no_surat
 * @property string $prefix
 * @property string $pj
 * @property string $perihal
 * @property string $tgl_terbit
 * @property int $status
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property-read \App\Models\Pegawai|null $penanggungJawab
 * @method static \Illuminate\Database\Eloquent\Builder|RsiaBerkasIht newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RsiaBerkasIht newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RsiaBerkasIht query()
 * @method static \Illuminate\Database\Eloquent\Builder|RsiaBerkasIht whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RsiaBerkasIht whereNoSurat($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RsiaBerkasIht whereNomor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RsiaBerkasIht wherePerihal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RsiaBerkasIht wherePj($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RsiaBerkasIht wherePrefix($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RsiaBerkasIht whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RsiaBerkasIht whereTglTerbit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RsiaBerkasIht whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class RsiaBerkasIht extends Model
{
    use HasFactory, HasCompositeKey;

    protected $table = 'rsia_berkas_iht';

    protected $primaryKey = ['nomor', 'tgl_terbit'];

    protected $guarded = [];


    public function penanggungJawab()
    {
        return $this->belongsTo(Pegawai::class, 'pj', 'nik')->select('nik', 'nama');
    }
}
