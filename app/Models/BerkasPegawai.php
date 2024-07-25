<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\BerkasPegawai
 *
 * @property string $nik
 * @property string $tgl_uploud
 * @property string $kode_berkas
 * @property string $berkas
 * @property-read \App\Models\Pegawai $pegawai
 * @method static \Illuminate\Database\Eloquent\Builder|BerkasPegawai newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BerkasPegawai newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BerkasPegawai query()
 * @method static \Illuminate\Database\Eloquent\Builder|BerkasPegawai whereBerkas($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BerkasPegawai whereKodeBerkas($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BerkasPegawai whereNik($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BerkasPegawai whereTglUploud($value)
 * @mixin \Eloquent
 */
class BerkasPegawai extends Model
{
    use HasFactory;

    protected $table = 'berkas_pegawai';

    protected $primaryKey = 'kode_berkas';

    protected $guarded = [];
    
    protected $keyType = 'string';
    
    public $incrementing = false;

    public $timestamps = false;
    
    public function masterBerkasPegawai()
    {
        return $this->belongsTo(MasterBerkasPegawai::class, 'kode_berkas', 'kode');
    }

    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class, 'nik', 'nik');
    }
}
