<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\PenilaianMedisIgd
 *
 * @property string $no_rawat
 * @property string $tanggal
 * @property string $kd_dokter
 * @property string $anamnesis
 * @property string $hubungan
 * @property string $keluhan_utama
 * @property string $rps
 * @property string $rpd
 * @property string $rpk
 * @property string $rpo
 * @property string $alergi
 * @property string $keadaan
 * @property string $gcs
 * @property string $kesadaran
 * @property string $td
 * @property string $nadi
 * @property string $rr
 * @property string $suhu
 * @property string $spo
 * @property string $bb
 * @property string $tb
 * @property string $kepala
 * @property string $mata
 * @property string $gigi
 * @property string $leher
 * @property string $thoraks
 * @property string $abdomen
 * @property string $genital
 * @property string $ekstremitas
 * @property string $ket_fisik
 * @property string $ket_lokalis
 * @property string $ekg
 * @property string $rad
 * @property string $lab
 * @property string $diagnosis
 * @property string $tata
 * @property-read \App\Models\Dokter $dokter
 * @method static \Illuminate\Database\Eloquent\Builder|PenilaianMedisIgd newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PenilaianMedisIgd newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PenilaianMedisIgd query()
 * @method static \Illuminate\Database\Eloquent\Builder|PenilaianMedisIgd whereAbdomen($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PenilaianMedisIgd whereAlergi($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PenilaianMedisIgd whereAnamnesis($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PenilaianMedisIgd whereBb($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PenilaianMedisIgd whereDiagnosis($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PenilaianMedisIgd whereEkg($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PenilaianMedisIgd whereEkstremitas($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PenilaianMedisIgd whereGcs($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PenilaianMedisIgd whereGenital($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PenilaianMedisIgd whereGigi($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PenilaianMedisIgd whereHubungan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PenilaianMedisIgd whereKdDokter($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PenilaianMedisIgd whereKeadaan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PenilaianMedisIgd whereKeluhanUtama($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PenilaianMedisIgd whereKepala($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PenilaianMedisIgd whereKesadaran($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PenilaianMedisIgd whereKetFisik($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PenilaianMedisIgd whereKetLokalis($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PenilaianMedisIgd whereLab($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PenilaianMedisIgd whereLeher($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PenilaianMedisIgd whereMata($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PenilaianMedisIgd whereNadi($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PenilaianMedisIgd whereNoRawat($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PenilaianMedisIgd whereRad($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PenilaianMedisIgd whereRpd($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PenilaianMedisIgd whereRpk($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PenilaianMedisIgd whereRpo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PenilaianMedisIgd whereRps($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PenilaianMedisIgd whereRr($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PenilaianMedisIgd whereSpo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PenilaianMedisIgd whereSuhu($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PenilaianMedisIgd whereTanggal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PenilaianMedisIgd whereTata($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PenilaianMedisIgd whereTb($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PenilaianMedisIgd whereTd($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PenilaianMedisIgd whereThoraks($value)
 * @mixin \Eloquent
 */
class PenilaianMedisIgd extends Model
{
    use HasFactory;

    protected $table = 'penilaian_medis_igd';

    protected $primaryKey = 'no_rawat';

    public $incrementing = false;

    public $timestamps = false;

    protected $guarded = [];


    /**
     * Define a relationship with the Dokter model.
     *
     * This function establishes a belongsTo relationship between the PenilaianMedisIgd model
     * and the Dokter model using the 'kd_dokter' foreign key.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function dokter()
    {
        return $this->belongsTo(Dokter::class, 'kd_dokter', 'kd_dokter');
    }
}
