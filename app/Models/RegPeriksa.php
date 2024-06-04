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
     * Get the pasien that owns the registrasi.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function pasien()
    {
        return $this->belongsTo(Pasien::class, 'no_rkm_medis', 'no_rkm_medis');
    }

    /**
     * Get the pasien that owns the registrasi.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function pasienSomeData()
    {
        return $this->belongsTo(Pasien::class, 'no_rkm_medis', 'no_rkm_medis')
            ->select('no_rkm_medis', 'nm_pasien', 'jk', 'tmp_lahir', 'tgl_lahir');
    }

    /**
     * Get the dokter that owns the registrasi.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
    */
    public function dokter()
    {
        return $this->belongsTo(Dokter::class, 'kd_dokter', 'kd_dokter')->select('kd_dokter', 'nm_dokter');
    }

    /**
     * Get the poliklinik that owns the registrasi.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     * 
    */
    public function poliklinik()
    {
        return $this->belongsTo(Poliklinik::class, 'kd_poli', 'kd_poli');
    }

    /**
     * Get the poliklinik that owns the registrasi.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function caraBayar()
    {
        return $this->belongsTo(Penjab::class, 'kd_pj', 'kd_pj');
    }

    /**
     * Get the resep obat that owns the registrasi.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function resepObat()
    {
        return $this->hasMany(ResepObat::class, 'no_rawat', 'no_rawat');
    }

    /**
     * Get the pemeriksaan ranap that owns the registrasi.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     * */
    public function pemeriksaanRanap()
    {
        return $this->hasMany(PemeriksaanRanap::class, 'no_rawat', 'no_rawat');
    }

    /**
     * Get the pemeriksaan ralan that owns the registrasi.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function pemeriksaanRalan()
    {
        return $this->hasMany(PemeriksaanRalan::class, 'no_rawat', 'no_rawat');
    }

    /**
     * Get the periksa lab that owns the registrasi.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     * */
    public function periksaLab()
    {
        return $this->hasMany(PeriksaLab::class, 'no_rawat', 'no_rawat');
    }

    /**
     * Get the periksa radiologi that owns the registrasi.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     * */
    public function periksaRadiologi()
    {
        return $this->hasMany(PeriksaRadiologi::class, 'no_rawat', 'no_rawat');
    }

    public function detailPemberianObat()
    {
        return $this->hasMany(DetailPemberianObat::class, 'no_rawat', 'no_rawat');
    }
}
