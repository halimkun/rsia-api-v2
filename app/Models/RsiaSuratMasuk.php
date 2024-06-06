<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\RsiaSuratMasuk
 *
 * @property int $no
 * @property string $no_simrs
 * @property string|null $no_surat
 * @property string $pengirim
 * @property string|null $tgl_surat
 * @property string $perihal
 * @property string|null $pelaksanaan
 * @property string|null $pelaksanaan_end
 * @property string|null $tempat
 * @property string $ket
 * @property string $berkas
 * @property string $status
 * @method static \Illuminate\Database\Eloquent\Builder|RsiaSuratMasuk newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RsiaSuratMasuk newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RsiaSuratMasuk query()
 * @method static \Illuminate\Database\Eloquent\Builder|RsiaSuratMasuk whereBerkas($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RsiaSuratMasuk whereKet($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RsiaSuratMasuk whereNo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RsiaSuratMasuk whereNoSimrs($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RsiaSuratMasuk whereNoSurat($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RsiaSuratMasuk wherePelaksanaan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RsiaSuratMasuk wherePelaksanaanEnd($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RsiaSuratMasuk wherePengirim($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RsiaSuratMasuk wherePerihal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RsiaSuratMasuk whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RsiaSuratMasuk whereTempat($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RsiaSuratMasuk whereTglSurat($value)
 * @mixin \Eloquent
 */
class RsiaSuratMasuk extends Model
{
    use HasFactory;

    protected $table = 'rsia_surat_masuk';

    protected $primaryKey = 'no';

    protected $guarded = ['no'];

    public $timestamps = false;

    protected $casts = [
        'nosimrs' => 'date',
        'no_surat' => 'string',
    ];
}
