<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Thiagoprz\CompositeKey\HasCompositeKey;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * App\Models\RekapPresensi
 *
 * @property int $id
 * @property string $shift
 * @property string $jam_datang
 * @property string|null $jam_pulang
 * @property string $status
 * @property string $keterlambatan
 * @property string|null $durasi
 * @property string $keterangan
 * @property string $photo
 * @property-read \App\Models\Pegawai $pegawai
 * @method static \Illuminate\Database\Eloquent\Builder|RekapPresensi newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RekapPresensi newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RekapPresensi query()
 * @method static \Illuminate\Database\Eloquent\Builder|RekapPresensi whereDurasi($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RekapPresensi whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RekapPresensi whereJamDatang($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RekapPresensi whereJamPulang($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RekapPresensi whereKeterangan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RekapPresensi whereKeterlambatan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RekapPresensi wherePhoto($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RekapPresensi whereShift($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RekapPresensi whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RekapPresensi withDatang($date)
 * @method static \Illuminate\Database\Eloquent\Builder|RekapPresensi withId($nik)
 * @method static \Illuminate\Database\Eloquent\Builder|RekapPresensi withPulang($date)
 * @method static \Illuminate\Database\Eloquent\Builder|RekapPresensi withRange($start, $end)
 * @mixin \Eloquent
 */
class RekapPresensi extends Model
{
    use HasFactory, HasCompositeKey;

    protected $table = 'rekap_presensi';

    protected $primaryKey = ['id', 'jam_datang'];

    protected $guarded = [];


    /**
     * Get the pegawai that owns the RekapPresensi
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function scopeWithId($query, $nik)
    {
        $pegawai = Pegawai::select('id')->where('nik', $nik)->first();
        $id      = $pegawai->id;

        $q = $query->where('id', $id);
        return $q;
    }

    /**
     * Get the pegawai that owns the RekapPresensi
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function scopeWithDatang($query, $date)
    {
        return $query->whereDate('jam_datang', $date);
    }

    /**
     * Get the pegawai that owns the RekapPresensi
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function scopeWithPulang($query, $date)
    {
        return $query->whereDate('jam_pulang', $date);
    }

    /**
     * Get the pegawai that owns the RekapPresensi
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function scopeWithRange($query, $start, $end)
    {
        return $query->whereBetween('jam_datang', [$start, $end]);
    }
    
    /**
     * Define a relationship with the Pegawai model.
     *
     * This function establishes a belongsTo relationship between the RekapPresensi model
     * and the Pegawai model using the 'id' foreign key.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class, 'id', 'id');
    }
}
