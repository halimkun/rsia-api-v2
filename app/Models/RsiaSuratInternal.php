<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\RsiaSuratInternal
 *
 * @property string $no_surat
 * @property string|null $perihal
 * @property string|null $tempat
 * @property string|null $pj
 * @property string $tgl_terbit
 * @property string|null $tanggal
 * @property string|null $catatan
 * @property string|null $status
 * @property string $created_at
 * @property-read \App\Models\Pegawai|null $penanggung_jawab
 * @method static \Illuminate\Database\Eloquent\Builder|RsiaSuratInternal newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RsiaSuratInternal newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RsiaSuratInternal query()
 * @method static \Illuminate\Database\Eloquent\Builder|RsiaSuratInternal whereCatatan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RsiaSuratInternal whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RsiaSuratInternal whereNoSurat($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RsiaSuratInternal wherePerihal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RsiaSuratInternal wherePj($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RsiaSuratInternal whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RsiaSuratInternal whereTanggal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RsiaSuratInternal whereTempat($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RsiaSuratInternal whereTglTerbit($value)
 * @mixin \Eloquent
 */
class RsiaSuratInternal extends Model
{
    use HasFactory;

    protected $table = 'rsia_surat_internal';

    protected $primaryKey = 'no_surat';

    protected $keyType = 'string';

    protected $casts = [
        'no_surat' => 'string',
    ];

    protected $guarded = [];

    public $incrementing = false;

    public $timestamps = false;
    
    
    public function penerimaUndangan()
    {
        return $this->morphMany(RsiaPenerimaUndangan::class, 'undangan');
    }

    public function penanggungJawab()
    {
        return $this->belongsTo(Pegawai::class, 'pj', 'nik');
    }

    public function penanggungJawabSimple()
    {
        return $this->belongsTo(Pegawai::class, 'pj', 'nik')->select('nik', 'nama');
    }
}
