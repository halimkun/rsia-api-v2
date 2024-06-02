<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Thiagoprz\CompositeKey\HasCompositeKey;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BookingRegistrasi extends Model
{
    use HasFactory, HasCompositeKey;

    /**
     * The table associated with the model.
     * 
     * @var string
     * */ 
    protected $table = 'booking_registrasi';

    /**
     * The primary key for the model.
     * 
     * @var array[string]
     * */
    protected $primaryKey = ['no_rkm_medis', 'tanggal_periksa'];

    /**
     * The attributes that are mass assignable.
     * 
     * @var array
     * */
    protected $guarded = [];

    /**
     * Indicates if the model should be timestamped.
     * 
     * @var bool
     * */
    public $timestamps = false;

    /**
     * The "type" of the auto-incrementing ID.
     * 
     * @var string
     * */
    public $incrementing = false;

    /**
     * The attributes that should be cast.
     * 
     * @var array
     * */
    protected $casts = [
        'waktu_kunjungan' => 'datetime',
    ];

    /**
     * Get the pasien that owns the booking registrasi.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     * */
    public function penjab()
    {
        return $this->belongsTo(Penjab::class, 'kd_pj', 'kd_pj');
    }

    /**
     * Get the pasien that owns the booking registrasi.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     * */
    public function pasien()
    {
        return $this->belongsTo(Pasien::class, 'no_rkm_medis', 'no_rkm_medis');
    }

    /**
     * Get the dokter that owns the booking registrasi.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     * */
    public function dokter()
    {
        return $this->belongsTo(Dokter::class, 'kd_dokter', 'kd_dokter');
    }

    /**
     * Get the poliklinik that owns the booking registrasi.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     * */
    public function poli()
    {
        return $this->belongsTo(Poliklinik::class, 'kd_poli', 'kd_poli');
    }
}
