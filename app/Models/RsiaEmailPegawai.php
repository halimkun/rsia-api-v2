<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\RsiaEmailPegawai
 *
 * @property string $nik
 * @property string|null $email
 * @property-read \App\Models\Pegawai $pegawai
 * @property-read \App\Models\Petugas $petugas
 * @method static \Illuminate\Database\Eloquent\Builder|RsiaEmailPegawai newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RsiaEmailPegawai newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RsiaEmailPegawai query()
 * @method static \Illuminate\Database\Eloquent\Builder|RsiaEmailPegawai whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RsiaEmailPegawai whereNik($value)
 * @mixin \Eloquent
 */
class RsiaEmailPegawai extends Model
{
    use HasFactory;

    // table name
    protected $table = 'rsia_email_pegawai';

    // primary key
    protected $primaryKey = 'nik';

    // key type
    public $keyType = 'string';

    // guarded columns
    protected $guarded = [];

    // timestamps
    public $timestamps = false;

    
    /**
     * Get the pegawai that owns the RsiaEmailPegawai
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class, 'nik', 'nik');
    }

    /**
     * Get the petugas that owns the RsiaEmailPegawai
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function petugas()
    {
        return $this->belongsTo(Petugas::class, 'nik', 'nip');
    }
}
