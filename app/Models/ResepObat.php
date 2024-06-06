<?php

namespace App\Models;

use Awobaz\Compoships\Compoships;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * App\Models\ResepObat
 *
 * @property string $no_resep
 * @property string|null $tgl_perawatan
 * @property string $jam
 * @property string $no_rawat
 * @property string $kd_dokter
 * @property string|null $tgl_peresepan
 * @property string|null $jam_peresepan
 * @property string|null $status
 * @property string $tgl_penyerahan
 * @property string $jam_penyerahan
 * @property-read \App\Models\Dokter $dokter
 * @property-read \App\Models\RegPeriksa $regPeriksa
 * @method static \Illuminate\Database\Eloquent\Builder|ResepObat newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ResepObat newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ResepObat query()
 * @method static \Illuminate\Database\Eloquent\Builder|ResepObat whereJam($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ResepObat whereJamPenyerahan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ResepObat whereJamPeresepan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ResepObat whereKdDokter($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ResepObat whereNoRawat($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ResepObat whereNoResep($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ResepObat whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ResepObat whereTglPenyerahan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ResepObat whereTglPerawatan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ResepObat whereTglPeresepan($value)
 * @mixin \Eloquent
 */
class ResepObat extends Model
{
    use HasFactory, Compoships;

    protected $table = 'resep_obat';

    protected $primaryKey = 'no_resep';

    protected $guarded = [];

    protected $casts = [
        'no_rawat' => 'string',
        'no_resep' => 'string',
    ];
    
    public $timestamps = false;

    public $incrementing = false;


    public function dokter()
    {
        return $this->belongsTo(Dokter::class, 'kd_dokter', 'kd_dokter');
    }

    public function regPeriksa()
    {
        return $this->belongsTo(RegPeriksa::class, 'no_rawat', 'no_rawat');
    }

    public function detail()
    {
        return $this->hasMany(
            DetailPemberianObat::class, 
            ["tgl_perawatan", "jam", "no_rawat"],
            ["tgl_perawatan", "jam", "no_rawat"]
        )->select(["tgl_perawatan","jam","no_rawat","jml","kode_brng","status","kd_bangsal"]);
    }
}

