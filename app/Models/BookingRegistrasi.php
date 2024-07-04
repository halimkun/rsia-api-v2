<?php

namespace App\Models;

use Awobaz\Compoships\Compoships;
use Illuminate\Database\Eloquent\Model;
use Thiagoprz\CompositeKey\HasCompositeKey;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * App\Models\BookingRegistrasi
 *
 * @property string|null $tanggal_booking
 * @property string|null $jam_booking
 * @property string $no_rkm_medis
 * @property string $tanggal_periksa
 * @property string|null $kd_dokter
 * @property string|null $kd_poli
 * @property string|null $no_reg
 * @property string|null $kd_pj
 * @property int|null $limit_reg
 * @property \Illuminate\Support\Carbon|null $waktu_kunjungan
 * @property string|null $status
 * @property-read \App\Models\Dokter|null $dokter
 * @property-read \App\Models\Pasien $pasien
 * @property-read \App\Models\Penjab|null $penjab
 * @property-read \App\Models\Poliklinik|null $poli
 * @method static \Illuminate\Database\Eloquent\Builder|BookingRegistrasi newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BookingRegistrasi newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BookingRegistrasi query()
 * @method static \Illuminate\Database\Eloquent\Builder|BookingRegistrasi whereJamBooking($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BookingRegistrasi whereKdDokter($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BookingRegistrasi whereKdPj($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BookingRegistrasi whereKdPoli($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BookingRegistrasi whereLimitReg($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BookingRegistrasi whereNoReg($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BookingRegistrasi whereNoRkmMedis($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BookingRegistrasi whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BookingRegistrasi whereTanggalBooking($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BookingRegistrasi whereTanggalPeriksa($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BookingRegistrasi whereWaktuKunjungan($value)
 * @mixin \Eloquent
 */
class BookingRegistrasi extends Model
{
    use HasFactory, HasCompositeKey, Compoships;

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

    // scope check stts on regPeriksa
    public function scopeStatusBelum($query, $no_rkm_medis, $tanggal_periksa)
    {
        return $query->whereHas('regPeriksa', function ($query) use ($no_rkm_medis, $tanggal_periksa) {
            $query->where('no_rkm_medis', $no_rkm_medis);
            $query->where('tgl_registrasi', $tanggal_periksa);
            $query->where('stts', 'Belum');
        });
    }

    /**
     * Get the regPeriksa that owns the booking registrasi.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     * */
    public function regPeriksa()
    {
        return $this->belongsTo(RegPeriksa::class, ['no_rkm_medis', 'tanggal_periksa'], ['no_rkm_medis', 'tgl_registrasi']);
    }

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
        return $this->belongsTo(Pasien::class, 'no_rkm_medis', 'no_rkm_medis')->select('no_rkm_medis', 'nm_pasien', 'jk', 'tmp_lahir', 'tgl_lahir', 'email');
    }

    /**
     * Get the dokter that owns the booking registrasi.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     * */
    public function dokter()
    {
        return $this->belongsTo(Dokter::class, 'kd_dokter', 'kd_dokter')->select('kd_dokter', 'nm_dokter', 'kd_sps');
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
