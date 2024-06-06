<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Thiagoprz\CompositeKey\HasCompositeKey;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * App\Models\Operasi
 *
 * @property string $no_rawat
 * @property string $tgl_operasi
 * @property string $jenis_anasthesi
 * @property string|null $kategori
 * @property string $operator1
 * @property string $operator2
 * @property string $operator3
 * @property string $asisten_operator1
 * @property string $asisten_operator2
 * @property string|null $asisten_operator3
 * @property string|null $instrumen
 * @property string $dokter_anak
 * @property string $perawaat_resusitas
 * @property string $dokter_anestesi
 * @property string $asisten_anestesi
 * @property string|null $asisten_anestesi2
 * @property string $bidan
 * @property string|null $bidan2
 * @property string|null $bidan3
 * @property string $perawat_luar
 * @property string|null $omloop
 * @property string|null $omloop2
 * @property string|null $omloop3
 * @property string|null $omloop4
 * @property string|null $omloop5
 * @property string|null $dokter_pjanak
 * @property string|null $dokter_umum
 * @property string $kode_paket
 * @property float $biayaoperator1
 * @property float $biayaoperator2
 * @property float $biayaoperator3
 * @property float $biayaasisten_operator1
 * @property float $biayaasisten_operator2
 * @property float|null $biayaasisten_operator3
 * @property float|null $biayainstrumen
 * @property float $biayadokter_anak
 * @property float $biayaperawaat_resusitas
 * @property float $biayadokter_anestesi
 * @property float $biayaasisten_anestesi
 * @property float|null $biayaasisten_anestesi2
 * @property float $biayabidan
 * @property float|null $biayabidan2
 * @property float|null $biayabidan3
 * @property float $biayaperawat_luar
 * @property float $biayaalat
 * @property float $biayasewaok
 * @property float|null $akomodasi
 * @property float $bagian_rs
 * @property float|null $biaya_omloop
 * @property float|null $biaya_omloop2
 * @property float|null $biaya_omloop3
 * @property float|null $biaya_omloop4
 * @property float|null $biaya_omloop5
 * @property float|null $biayasarpras
 * @property float|null $biaya_dokter_pjanak
 * @property float|null $biaya_dokter_umum
 * @property string|null $status
 * @method static \Illuminate\Database\Eloquent\Builder|Operasi newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Operasi newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Operasi query()
 * @method static \Illuminate\Database\Eloquent\Builder|Operasi whereAkomodasi($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Operasi whereAsistenAnestesi($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Operasi whereAsistenAnestesi2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Operasi whereAsistenOperator1($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Operasi whereAsistenOperator2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Operasi whereAsistenOperator3($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Operasi whereBagianRs($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Operasi whereBiayaDokterPjanak($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Operasi whereBiayaDokterUmum($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Operasi whereBiayaOmloop($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Operasi whereBiayaOmloop2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Operasi whereBiayaOmloop3($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Operasi whereBiayaOmloop4($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Operasi whereBiayaOmloop5($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Operasi whereBiayaalat($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Operasi whereBiayaasistenAnestesi($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Operasi whereBiayaasistenAnestesi2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Operasi whereBiayaasistenOperator1($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Operasi whereBiayaasistenOperator2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Operasi whereBiayaasistenOperator3($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Operasi whereBiayabidan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Operasi whereBiayabidan2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Operasi whereBiayabidan3($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Operasi whereBiayadokterAnak($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Operasi whereBiayadokterAnestesi($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Operasi whereBiayainstrumen($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Operasi whereBiayaoperator1($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Operasi whereBiayaoperator2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Operasi whereBiayaoperator3($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Operasi whereBiayaperawaatResusitas($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Operasi whereBiayaperawatLuar($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Operasi whereBiayasarpras($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Operasi whereBiayasewaok($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Operasi whereBidan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Operasi whereBidan2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Operasi whereBidan3($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Operasi whereDokterAnak($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Operasi whereDokterAnestesi($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Operasi whereDokterPjanak($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Operasi whereDokterUmum($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Operasi whereInstrumen($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Operasi whereJenisAnasthesi($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Operasi whereKategori($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Operasi whereKodePaket($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Operasi whereNoRawat($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Operasi whereOmloop($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Operasi whereOmloop2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Operasi whereOmloop3($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Operasi whereOmloop4($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Operasi whereOmloop5($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Operasi whereOperator1($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Operasi whereOperator2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Operasi whereOperator3($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Operasi wherePerawaatResusitas($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Operasi wherePerawatLuar($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Operasi whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Operasi whereTglOperasi($value)
 * @mixin \Eloquent
 */
class Operasi extends Model
{
    use HasFactory, HasCompositeKey;

    protected $table = 'operasi';

    protected $primaryKey = ["no_rawat", "tgl_operasi", "kode_paket"];

    protected $guarded = [];

    protected $casts = [
        'no_rawat' => 'string',
    ];

    public $incrementing = false;

    public $timestamps = false;
}
