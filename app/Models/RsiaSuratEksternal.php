<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\RsiaSuratEksternal
 *
 * @property string $no_surat
 * @property string|null $perihal
 * @property string|null $alamat
 * @property string $tgl_terbit
 * @property string|null $pj
 * @property string|null $tanggal
 * @property string|null $created_at
 * @property-read \App\Models\Pegawai|null $penanggung_jawab
 * @method static \Illuminate\Database\Eloquent\Builder|RsiaSuratEksternal newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RsiaSuratEksternal newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RsiaSuratEksternal query()
 * @method static \Illuminate\Database\Eloquent\Builder|RsiaSuratEksternal whereAlamat($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RsiaSuratEksternal whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RsiaSuratEksternal whereNoSurat($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RsiaSuratEksternal wherePerihal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RsiaSuratEksternal wherePj($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RsiaSuratEksternal whereTanggal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RsiaSuratEksternal whereTglTerbit($value)
 * @mixin \Eloquent
 */
class RsiaSuratEksternal extends Model
{
    use HasFactory;

    protected $table = 'rsia_surat_eksternal';

    protected $primaryKey = 'no_surat';

    protected $keyType = 'string';

    protected $casts = [
        'no_surat' => 'string',
    ];

    protected $guarded = [];

    public $incrementing = false;

    public $timestamps = false;


    public function penanggungJawab()
    {
        return $this->belongsTo(Pegawai::class, 'pj', 'nik');
    }

    public function penanggungJawabSimple()
    {
        return $this->belongsTo(Pegawai::class, 'pj', 'nik')->select('nik', 'nama');
    }
}
