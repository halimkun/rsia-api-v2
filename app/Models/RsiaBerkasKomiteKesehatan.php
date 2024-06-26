<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Thiagoprz\CompositeKey\HasCompositeKey;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * App\Models\RsiaBerkasKomiteKesehatan
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
 * @method static \Illuminate\Database\Eloquent\Builder|RsiaBerkasKomiteKesehatan newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RsiaBerkasKomiteKesehatan newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RsiaBerkasKomiteKesehatan query()
 * @method static \Illuminate\Database\Eloquent\Builder|RsiaBerkasKomiteKesehatan whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RsiaBerkasKomiteKesehatan whereNoSurat($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RsiaBerkasKomiteKesehatan whereNomor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RsiaBerkasKomiteKesehatan wherePerihal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RsiaBerkasKomiteKesehatan wherePj($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RsiaBerkasKomiteKesehatan wherePrefix($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RsiaBerkasKomiteKesehatan whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RsiaBerkasKomiteKesehatan whereTglTerbit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RsiaBerkasKomiteKesehatan whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class RsiaBerkasKomiteKesehatan extends Model
{
    use HasFactory, HasCompositeKey;

    protected $table = 'rsia_berkas_komite_kesehatan';

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
