<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Thiagoprz\CompositeKey\HasCompositeKey;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * App\Models\RsiaBerkasRadiologi
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
 * @method static \Illuminate\Database\Eloquent\Builder|RsiaBerkasRadiologi newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RsiaBerkasRadiologi newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RsiaBerkasRadiologi query()
 * @method static \Illuminate\Database\Eloquent\Builder|RsiaBerkasRadiologi whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RsiaBerkasRadiologi whereNoSurat($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RsiaBerkasRadiologi whereNomor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RsiaBerkasRadiologi wherePerihal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RsiaBerkasRadiologi wherePj($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RsiaBerkasRadiologi wherePrefix($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RsiaBerkasRadiologi whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RsiaBerkasRadiologi whereTglTerbit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RsiaBerkasRadiologi whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class RsiaBerkasRadiologi extends Model
{
    use HasFactory, HasCompositeKey;

    protected $table = 'rsia_berkas_radiologi';

    protected $primaryKey = ['nomor', 'tgl_terbit'];

    protected $guarded = [];


    public function penanggungJawab()
    {
        return $this->belongsTo(Pegawai::class, 'pj', 'nik')->select('nik', 'nama');
    }
}
