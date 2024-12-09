<?php

namespace App\Models;

use Awobaz\Compoships\Compoships;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Thiagoprz\CompositeKey\HasCompositeKey;

class PemeriksaanRanapKlaim extends Model
{
    use HasFactory, HasCompositeKey, Compoships;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'pemeriksaan_ranap_klaim';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = ['no_rawat', 'tgl_perawatan', 'jam_rawat'];

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    public $incrementing = false;

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;


    /**
     * Get the regPeriksa that owns the PemeriksaanRanapKlaim
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function petugas()
    {
        return $this->belongsTo(Petugas::class, 'nip', 'nip');
    }
}
