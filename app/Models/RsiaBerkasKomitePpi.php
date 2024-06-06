<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Thiagoprz\CompositeKey\HasCompositeKey;

/**
 * App\Models\RsiaBerkasKomitePpi
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
 * @property-read \App\Models\Pegawai|null $penanggungjawab
 * @method static \Illuminate\Database\Eloquent\Builder|RsiaBerkasKomitePpi newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RsiaBerkasKomitePpi newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RsiaBerkasKomitePpi query()
 * @method static \Illuminate\Database\Eloquent\Builder|RsiaBerkasKomitePpi whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RsiaBerkasKomitePpi whereNoSurat($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RsiaBerkasKomitePpi whereNomor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RsiaBerkasKomitePpi wherePerihal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RsiaBerkasKomitePpi wherePj($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RsiaBerkasKomitePpi wherePrefix($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RsiaBerkasKomitePpi whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RsiaBerkasKomitePpi whereTglTerbit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RsiaBerkasKomitePpi whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class RsiaBerkasKomitePpi extends Model
{
    use HasFactory, HasCompositeKey;

    protected $table = 'rsia_berkas_komite_ppi';

    protected $primaryKey = ['nomor', 'tgl_terbit'];

    protected $guarded = [];


    public function exposedScopes()
    {
        return [];
    }

    public function searchableBy()
    {
        return ['perihal', 'penanggungjawab.nama'];
    }

    public function filterableBy()
    {
        return ['tgl_terbit', 'pj', 'status'];
    }

    public function sortableBy()
    {
        return ['perihal', 'tgl_terbit', 'status', 'created_at', 'updated_at'];
    }

    public function aggregatableBy()
    {
        return [];
    }

    public function includableBy()
    {
        return ['penanggungjawab'];
    }


    public function penanggungjawab()
    {
        return $this->belongsTo(Pegawai::class, 'pj', 'nik')->select('nik', 'nama');
    }
}
