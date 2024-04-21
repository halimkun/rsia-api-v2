<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\MasterBerkasPegawai
 *
 * @property string $kode
 * @property string $kategori
 * @property string $nama_berkas
 * @property int $no_urut
 * @method static \Illuminate\Database\Eloquent\Builder|MasterBerkasPegawai newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MasterBerkasPegawai newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MasterBerkasPegawai query()
 * @method static \Illuminate\Database\Eloquent\Builder|MasterBerkasPegawai whereKategori($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MasterBerkasPegawai whereKode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MasterBerkasPegawai whereNamaBerkas($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MasterBerkasPegawai whereNoUrut($value)
 * @mixin \Eloquent
 */
class MasterBerkasPegawai extends Model
{
    use HasFactory;

    protected $table = 'master_berkas_pegawai';

    protected $primaryKey = 'kode';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $guarded = [];

    public $timestamps = false;

}
