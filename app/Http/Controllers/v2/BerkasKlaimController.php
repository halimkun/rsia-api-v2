<?php

namespace App\Http\Controllers\v2;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\App;
use App\Http\Controllers\Controller;
use Webklex\PDFMerger\Facades\PDFMergerFacade as PDFMerger;

class BerkasKlaimController extends Controller
{
    /**
     * Ukuran kertas F4
     * 
     * @var array
     */
    protected $f4 = [0, 0, 609.448, 935.432];

    /**
     * Orientasi kertas
     * 
     * @var string
     */
    protected $orientation = 'portrait';

    protected $koorByDepartemen = [
        'Anak'      => 'Anak',
        'Kandungan' => 'Nifas',
        'BY'        => 'PERINATOLOGI',
        'VK'        => 'VK'
    ];

    /**
     * Objek merger
     * 
     * @var \Webklex\PDFMerger\PDFMerger
     */
    protected $oMerger;

    public function __construct()
    {
        $this->oMerger = PDFMerger::init();
    }

    /**
     * Cetak berkas klaim
     * 
     * @param string $sep
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function print($sep, Request $request)
    {
        // ========== BERKAS DATA
        $bSep              = \App\Models\BridgingSep::with(['pasien', 'reg_periksa', 'dokter.pegawai.sidikjari' => function ($q) {
            $q->select('id', \DB::raw('SHA1(sidikjari) as sidikjari'));
        }])->where('no_sep', $sep)->first();

        $diagnosa          = \App\Models\DiagnosaPasien::with('penyakit')->where('no_rawat', $bSep->no_rawat)->orderBy('prioritas', 'asc')->get();
        $prosedur          = \App\Models\ProsedurPasien::with('penyakit')->where('no_rawat', $bSep->no_rawat)->orderBy('prioritas', 'asc')->get();
        $pasien            = \App\Models\Pasien::where('no_rkm_medis', $bSep->nomr)->first();
        $regPeriksa        = \App\Models\RegPeriksa::where('no_rawat', $bSep->no_rawat)->first();
        $kamarInap         = \App\Models\KamarInap::with('kamar.bangsal')->where('no_rawat', $bSep->no_rawat)->orderBy('tgl_masuk', 'desc')->orderBy('jam_masuk', 'desc')->get();
        $resumePasienRanap = \App\Models\ResumePasienRanap::where('no_rawat', $bSep->no_rawat)->first();
        $spri             = \App\Models\BridgingSuratPriBpjs::where('no_rawat', $bSep->no_rawat)->first();

        // TTD DATA
        $ttdPasien         = \App\Models\RsiaVerifSep::where('no_sep', $sep)->first();
        $ttdResume         = \App\Models\Pegawai::with(['sidikjari' => function ($q) {
            $q->select('id', \DB::raw('SHA1(sidikjari) as sidikjari'));
        }, 'dep'])->whereHas('dep', function ($q) use ($kamarInap) {
            return $q->where('nama', \Illuminate\Support\Str::upper($this->getDepartemen($kamarInap)));
        })->where('status_koor', '1')->first();


        // ========== BERKAS
        $berkasSep = Pdf::loadView('berkas-klaim.partials.sep', [
            'sep'      => $bSep,
            'diagnosa' => $diagnosa,
            'prosedur' => $prosedur,
        ])->setPaper($this->f4, $this->orientation);
        $resumeMedis = Pdf::loadView('berkas-klaim.partials.resume-medis', [
            'sep'        => $bSep->withoutRelations(),
            'pasien'     => $pasien,
            'regPeriksa' => $regPeriksa,
            'kamarInap'  => $kamarInap,
            'resume'     => $resumePasienRanap,
            'ttdResume'  => $ttdResume,
            'ttdDpjp'    => $bSep->dokter->pegawai,
            'ttdPasien'  => $ttdPasien,
        ])->setPaper($this->f4, $this->orientation);
        $pri = Pdf::loadView('berkas-klaim.partials.spri', [
            'sep'    => $bSep,
            'pasien' => $pasien,
            'spri'   => $spri,
        ])->setPaper($this->f4, $this->orientation);


        // ========== MERGER PREPARE
        $this->oMerger->addString($berkasSep->output(), 'all');
        $this->oMerger->addString($resumeMedis->output(), 'all');
        $this->oMerger->addString($pri->output(), 'all');
        

        // ========== MERGE FINAL
        $this->oMerger->merge();
        $this->oMerger->setFileName('berkas-klaim-' . $sep . '.pdf');


        // ========== RESPONSE
        return response($this->oMerger->stream())
            ->header('Content-Type', 'application/pdf')
            ->header('Pragma', 'no-cache')
            ->header('Expires', '0');
    }

    private function getDepartemen($kamarInap)
    {
        $filteredKeys = array_filter(array_keys($this->koorByDepartemen), function ($key) use ($kamarInap) {
            return strpos($kamarInap[0]->kd_kamar, $key) !== false;
        });

        $values = array_values(array_intersect_key($this->koorByDepartemen, array_flip($filteredKeys)));

        return $values[0] ?? null;  // Return the first value or null if empty         
    }
}
