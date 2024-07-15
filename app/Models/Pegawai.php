<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Pegawai
 *
 * @property int $id
 * @property string $nik
 * @property string $nama
 * @property string $jk
 * @property string $jbtn
 * @property string $jnj_jabatan
 * @property string $kode_kelompok
 * @property string $kode_resiko
 * @property string $kode_emergency
 * @property string $status_koor
 * @property string $departemen
 * @property string $bidang
 * @property string $stts_wp
 * @property string $stts_kerja
 * @property string $npwp
 * @property string $pendidikan
 * @property float $gapok
 * @property string $tmp_lahir
 * @property string $tgl_lahir
 * @property string $alamat
 * @property string $kota
 * @property string $mulai_kerja
 * @property string $ms_kerja
 * @property string $indexins
 * @property string $bpd
 * @property string $rekening
 * @property string $stts_aktif
 * @property int $wajibmasuk
 * @property float $pengurang
 * @property int $indek
 * @property string|null $mulai_kontrak
 * @property int $cuti_diambil
 * @property float $dankes
 * @property string|null $photo
 * @property string $no_ktp
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\BerkasPegawai> $berkas
 * @property-read int|null $berkas_count
 * @property-read \App\Models\Departemen $dep
 * @method static \Illuminate\Database\Eloquent\Builder|Pegawai newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Pegawai newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Pegawai query()
 * @method static \Illuminate\Database\Eloquent\Builder|Pegawai whereAlamat($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pegawai whereBidang($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pegawai whereBpd($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pegawai whereCutiDiambil($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pegawai whereDankes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pegawai whereDepartemen($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pegawai whereGapok($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pegawai whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pegawai whereIndek($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pegawai whereIndexins($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pegawai whereJbtn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pegawai whereJk($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pegawai whereJnjJabatan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pegawai whereKodeEmergency($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pegawai whereKodeKelompok($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pegawai whereKodeResiko($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pegawai whereKota($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pegawai whereMsKerja($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pegawai whereMulaiKerja($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pegawai whereMulaiKontrak($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pegawai whereNama($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pegawai whereNik($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pegawai whereNoKtp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pegawai whereNpwp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pegawai wherePendidikan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pegawai wherePengurang($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pegawai wherePhoto($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pegawai whereRekening($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pegawai whereStatusKoor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pegawai whereSttsAktif($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pegawai whereSttsKerja($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pegawai whereSttsWp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pegawai whereTglLahir($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pegawai whereTmpLahir($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pegawai whereWajibmasuk($value)
 * @mixin \Eloquent
 */
class Pegawai extends Model
{
    use HasFactory;

    protected $table = 'pegawai';

    protected $primaryKey = 'nik';

    public $incrementing = false;

    public $timestamps = false;

    protected $keyType = 'string';

    protected $casts = [
        'nik' => 'string',
    ];

    protected $guarded = ['id'];

    // departemen on pegawai to dep_id on departemen
    public function dep()
    {
        return $this->belongsTo(Departemen::class, 'departemen', 'dep_id');
    }

    public function berkas()
    {
        return $this->hasMany(BerkasPegawai::class, 'nik', 'nik');
    }

    public function presensi()
    {
        return $this->hasMany(RekapPresensi::class, 'id', 'id');
    }
}
