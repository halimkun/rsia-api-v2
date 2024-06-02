<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RegPeriksa extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'reg_periksa';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'no_rawat';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'no_rawat' => 'string',
        'no_rkm_medis' => 'string',
    ];


    /**
     * Get the pasien that owns the booking registrasi.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function pasien()
    {
        return $this->belongsTo(Pasien::class, 'no_rkm_medis', 'no_rkm_medis');
    }

    /**
     * Get the pasien that owns the booking registrasi.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function pasienSomeData()
    {
        return $this->belongsTo(Pasien::class, 'no_rkm_medis', 'no_rkm_medis')
            ->select('no_rkm_medis', 'nm_pasien', 'jk', 'tmp_lahir', 'tgl_lahir');
    }
}
