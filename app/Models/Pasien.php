<?php

namespace App\Models;

use Laravel\Passport\HasApiTokens;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * App\Models\Pasien
 *
 * @property string $no_rkm_medis
 * @property string|null $nm_pasien
 * @property string|null $no_ktp
 * @property string|null $jk
 * @property string|null $tmp_lahir
 * @property string|null $tgl_lahir
 * @property string $nm_ibu
 * @property string|null $alamat
 * @property string|null $gol_darah
 * @property string|null $pekerjaan
 * @property string|null $stts_nikah
 * @property string|null $agama
 * @property string|null $tgl_daftar
 * @property string|null $no_tlp
 * @property string $umur
 * @property string $pnd
 * @property string|null $keluarga
 * @property string $namakeluarga
 * @property string $kd_pj
 * @property string|null $no_peserta
 * @property int $kd_kel
 * @property int $kd_kec
 * @property int $kd_kab
 * @property string $pekerjaanpj
 * @property string $alamatpj
 * @property string $kelurahanpj
 * @property string $kecamatanpj
 * @property string $kabupatenpj
 * @property string $perusahaan_pasien
 * @property int $suku_bangsa
 * @property int $bahasa_pasien
 * @property int $cacat_fisik
 * @property string $email
 * @property string $nip
 * @property int $kd_prop
 * @property string $propinsipj
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Laravel\Passport\Client> $clients
 * @property-read int|null $clients_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Laravel\Passport\Token> $tokens
 * @property-read int|null $tokens_count
 * @method static \Illuminate\Database\Eloquent\Builder|Pasien newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Pasien newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Pasien query()
 * @method static \Illuminate\Database\Eloquent\Builder|Pasien whereAgama($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pasien whereAlamat($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pasien whereAlamatpj($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pasien whereBahasaPasien($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pasien whereCacatFisik($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pasien whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pasien whereGolDarah($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pasien whereJk($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pasien whereKabupatenpj($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pasien whereKdKab($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pasien whereKdKec($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pasien whereKdKel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pasien whereKdPj($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pasien whereKdProp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pasien whereKecamatanpj($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pasien whereKeluarga($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pasien whereKelurahanpj($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pasien whereNamakeluarga($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pasien whereNip($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pasien whereNmIbu($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pasien whereNmPasien($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pasien whereNoKtp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pasien whereNoPeserta($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pasien whereNoRkmMedis($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pasien whereNoTlp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pasien wherePekerjaan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pasien wherePekerjaanpj($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pasien wherePerusahaanPasien($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pasien wherePnd($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pasien wherePropinsipj($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pasien whereSttsNikah($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pasien whereSukuBangsa($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pasien whereTglDaftar($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pasien whereTglLahir($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pasien whereTmpLahir($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pasien whereUmur($value)
 * @mixin \Eloquent
 */
class Pasien extends Authenticatable
{
    use HasFactory, HasApiTokens, Notifiable;

    protected $table = 'pasien';

    protected $primaryKey = 'no_rkm_medis';

    protected $hidden = ['no_ktp', 'no_peserta'];

    protected $guarded = [];

    protected $casts = [
        'no_rkm_medis' => 'string',
        'kd_pj' => 'string',
    ];

    public $timestamps = false;

    public $incrementing = false;

    /**
     * Specifies the user's FCM tokens
     *
     * @return string|array
     */
    public function routeNotificationForFcm()
    {
        return null;
    }
}
