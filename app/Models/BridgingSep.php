<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * App\Models\BridgingSep
 *
 * @property string $no_sep
 * @property string|null $no_rawat
 * @property string|null $tglsep
 * @property string|null $tglrujukan
 * @property string|null $no_rujukan
 * @property string|null $kdppkrujukan
 * @property string|null $nmppkrujukan
 * @property string|null $kdppkpelayanan
 * @property string|null $nmppkpelayanan
 * @property string|null $jnspelayanan
 * @property string|null $catatan
 * @property string|null $diagawal
 * @property string|null $nmdiagnosaawal
 * @property string|null $kdpolitujuan
 * @property string|null $nmpolitujuan
 * @property string|null $klsrawat
 * @property string $klsnaik
 * @property string $pembiayaan
 * @property string $pjnaikkelas
 * @property string|null $lakalantas
 * @property string|null $user
 * @property string|null $nomr
 * @property string|null $nama_pasien
 * @property string|null $tanggal_lahir
 * @property string|null $peserta
 * @property string|null $jkel
 * @property string|null $no_kartu
 * @property string|null $tglpulang
 * @property string $asal_rujukan
 * @property string $eksekutif
 * @property string $cob
 * @property string $notelep
 * @property string $katarak
 * @property string $tglkkl
 * @property string $keterangankkl
 * @property string $suplesi
 * @property string $no_sep_suplesi
 * @property string $kdprop
 * @property string $nmprop
 * @property string $kdkab
 * @property string $nmkab
 * @property string $kdkec
 * @property string $nmkec
 * @property string $noskdp
 * @property string $kddpjp
 * @property string $nmdpdjp
 * @property string $tujuankunjungan
 * @property string $flagprosedur
 * @property string $penunjang
 * @property string $asesmenpelayanan
 * @property string $kddpjplayanan
 * @property string $nmdpjplayanan
 * @method static \Illuminate\Database\Eloquent\Builder|BridgingSep newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BridgingSep newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BridgingSep query()
 * @method static \Illuminate\Database\Eloquent\Builder|BridgingSep whereAsalRujukan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BridgingSep whereAsesmenpelayanan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BridgingSep whereCatatan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BridgingSep whereCob($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BridgingSep whereDiagawal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BridgingSep whereEksekutif($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BridgingSep whereFlagprosedur($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BridgingSep whereJkel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BridgingSep whereJnspelayanan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BridgingSep whereKatarak($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BridgingSep whereKddpjp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BridgingSep whereKddpjplayanan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BridgingSep whereKdkab($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BridgingSep whereKdkec($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BridgingSep whereKdpolitujuan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BridgingSep whereKdppkpelayanan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BridgingSep whereKdppkrujukan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BridgingSep whereKdprop($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BridgingSep whereKeterangankkl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BridgingSep whereKlsnaik($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BridgingSep whereKlsrawat($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BridgingSep whereLakalantas($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BridgingSep whereNamaPasien($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BridgingSep whereNmdiagnosaawal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BridgingSep whereNmdpdjp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BridgingSep whereNmdpjplayanan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BridgingSep whereNmkab($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BridgingSep whereNmkec($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BridgingSep whereNmpolitujuan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BridgingSep whereNmppkpelayanan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BridgingSep whereNmppkrujukan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BridgingSep whereNmprop($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BridgingSep whereNoKartu($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BridgingSep whereNoRawat($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BridgingSep whereNoRujukan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BridgingSep whereNoSep($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BridgingSep whereNoSepSuplesi($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BridgingSep whereNomr($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BridgingSep whereNoskdp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BridgingSep whereNotelep($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BridgingSep wherePembiayaan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BridgingSep wherePenunjang($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BridgingSep wherePeserta($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BridgingSep wherePjnaikkelas($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BridgingSep whereSuplesi($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BridgingSep whereTanggalLahir($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BridgingSep whereTglkkl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BridgingSep whereTglpulang($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BridgingSep whereTglrujukan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BridgingSep whereTglsep($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BridgingSep whereTujuankunjungan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BridgingSep whereUser($value)
 * @mixin \Eloquent
 */
class BridgingSep extends Model
{
    /**
     * The table associated with the model
     * 
     * @var string
     * */
    protected $table = 'bridging_sep';

    /**
     * The primary key associated with the table
     * 
     * @var string
     * */
    protected $primaryKey = 'no_sep';

    /**
     * Indicates if the model's ID is auto-incrementing
     * 
     * @var bool
     * */
    public $incrementing = false;

    /**
     * Indicates if the model should be timestamped
     * 
     * @var bool
     * */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable
     * 
     * @var array
     * */
    protected $casts = [
        'no_rawat' => 'string',
    ];


    public function scopeWithLos($query)
    {
        return $query->whereHas('tanggal_pulang');
    }

    public function scopeHasBerkasPerawatan($query, $kode = '009')
    {
        return $query->whereHas('berkasPerawatan', function ($query) use ($kode) {
            $query->where('kode', $kode);
        });
    }

    public function scopeNotHasBerkasPerawatan($query, $kode = '009')
    {
        return $query->whereDoesntHave('berkasPerawatan', function ($query) use ($kode) {
            $query->where('kode', $kode);
        });
    }

    public function scopeNotHasStatusKlaim($query)
    {
        return $query->whereDoesntHave('status_klaim');
    }

    public function scopeSelectColumns($query, $columns = ['*'])
    {
        if (is_string($columns)) {
            $columns = array_map('trim', explode(',', $columns));
        }
        
        $columns = array_map(function ($column) {
            return 'bridging_sep.' . $column;
        }, $columns);

        return $query->select($columns);
    }

    /**
     * Get the status_klaim that owns the BridgingSep
     * 
     * @var array
     * */
    public function status_klaim()
    {
        return $this->hasOne(RsiaStatusKlaim::class, 'no_sep', 'no_sep');
    }

    /**
     * Get the reg_periksa that owns the BridgingSep
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     * */
    public function reg_periksa()
    {
        return $this->belongsTo(RegPeriksa::class, 'no_rawat', 'no_rawat')->select('no_rawat', 'tgl_registrasi', 'kd_dokter', 'jam_reg', 'no_reg', 'umurdaftar', 'sttsumur', 'status_poli', 'kd_poli');
    }

    public function poliklinik()
    {
        return $this->hasOneThrough(Poliklinik::class, RegPeriksa::class, 'no_rawat', 'kd_poli', 'no_rawat', 'kd_poli');
    }

    /**
     * Get the dokter that owns the BridgingSep
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasOneThrough
     * */
    public function dokter()
    {
        return $this->hasOneThrough(Dokter::class, RegPeriksa::class, 'no_rawat', 'kd_dokter', 'no_rawat', 'kd_dokter');
    }

    /**
     * Get the kamar_inap that owns the BridgingSep
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     * */
    public function kamar_inap()
    {
        return $this->belongsTo(KamarInap::class, 'no_rawat', 'no_rawat')->select('no_rawat', 'kd_kamar', 'diagnosa_awal', 'diagnosa_akhir', 'tgl_masuk', 'tgl_keluar', 'jam_masuk', 'jam_keluar', 'lama', 'stts_pulang');
    }

    /**
     * Get the tanggal_pulang that owns the BridgingSep
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     * */
    public function tanggal_pulang()
    {
        return $this->hasOne(KamarInap::class, 'no_rawat', 'no_rawat')->select('no_rawat', 'tgl_keluar', 'jam_keluar', 'lama', 'stts_pulang')->where('stts_pulang', '<>', 'Pindah Kamar');
    }

    /**
     * Get the pasien that owns the BridgingSep
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     * */
    public function pasien()
    {
        return $this->belongsTo(Pasien::class, 'nomr', 'no_rkm_medis')->select('no_rkm_medis', 'nm_pasien', 'tgl_lahir', 'jk', 'no_peserta', 'kd_pj');
    }

    /**
     * Get the rsia_grouping_chunks that owns the BridgingSep
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     * */
    public function chunk()
    {
        return $this->belongsTo(RsiaGroupingChunks::class, 'no_sep', 'no_sep');
    }

    /**
     * Get the bridging_surat_kontrol_bpjs that owns the BridgingSep
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     * */
    public function surat_kontrol(): HasOne
    {
        return $this->hasOne(BridgingSuratKontrolBpjs::class, 'no_surat', 'noskdp');
    }

    /**
     * Get the rsia_naik_kelas that owns the BridgingSep
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     * */
    public function naikKelas(): HasOne
    {
        return $this->hasOne(RsiaNaikKelas::class, 'no_sep', 'no_sep');
    }

    /**
     * Get the inacbg_grouping_stage12 that owns the BridgingSep
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     * */
    public function groupStage(): HasOne
    {
        return $this->hasOne(InacbgGropingStage12::class, 'no_sep', 'no_sep');
    }

    /**
     * Get the inacbg_klaim_baru2 that owns the BridgingSep
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     * */
    public function berkasPerawatan(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(BerkasDigitalPerawatan::class, 'no_rawat', 'no_rawat')->where('kode', '009');
    }

    /**
     * Get the rsia_klaim_terkirim that owns the BridgingSep
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     * */
    public function terkirim_online()
    {
        return $this->hasOne(InacbgDataTerkirim::class, 'no_sep', 'no_sep');
    }
    
    public function diagnosa()
    {
        return $this->hasMany(DiagnosaPasien::class, 'no_rawat', 'no_rawat')->orderBy('prioritas');
    }

    public function prosedur()
    {
        return $this->hasMany(ProsedurPasien::class, 'no_rawat', 'no_rawat')->orderBy('prioritas');
    }
}