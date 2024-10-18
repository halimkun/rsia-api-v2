<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\MLiteVedika
 * 
 * @property int $id
 * @property string $tanggal
 * @property string $no_rkm_medis
 * @property string $no_rawat
 * @property string $tgl_registrasi
 * @property string $nosep
 * @property string $jenis
 * @property string $status
 * @property string $username
 * 
 * @property-read \App\Models\Pasien $pasien
 * @property-read \App\Models\RegPeriksa $registrasi
 * @property-read \App\Models\BridgingSep $sep
 * 
 * @method static \Illuminate\Database\Eloquent\Builder|MLiteVedika newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MLiteVedika newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MLiteVedika query()
 * @method static \Illuminate\Database\Eloquent\Builder|MLiteVedika whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MLiteVedika whereJenis($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MLiteVedika whereNoRawat($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MLiteVedika whereNoRkmMedis($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MLiteVedika whereNosep($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MLiteVedika whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MLiteVedika whereTanggal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MLiteVedika whereTglRegistrasi($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MLiteVedika whereUsername($value)
 * 
 * @mixin \Eloquent
 */
class MLiteVedika extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'mlite_vedika';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ["tanggal", "no_rkm_medis", "no_rawat", "tgl_registrasi", "nosep", "jenis", "status", "username", "created_at", "updated_at"];

    /**
     * Get the pasien that owns the MLiteVedika
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function pasien()
    {
        return $this->belongsTo(Pasien::class, 'no_rkm_medis', 'no_rkm_medis');
    }

    /**
     * Get the registrasi that owns the MLiteVedika
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function registrasi()
    {
        return $this->belongsTo(RegPeriksa::class, 'no_rawat', 'no_rawat');
    }

    /**
     * Get the sep that owns the MLiteVedika
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function sep()
    {
        return $this->belongsTo(BridgingSep::class, 'nosep', 'no_sep');
    }

    /**
     * Get the feedback for the MLiteVedika
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function feedback()
    {
        return $this->belongsTo(MLiteVedikaFeeedback::class, 'created_at', 'created_at');
    }
}
