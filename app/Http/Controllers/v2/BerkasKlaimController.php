<?php

namespace App\Http\Controllers\v2;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Collection;
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

    /**
     * Koordinat berdasarkan departemen
     * 
     * @var array
     */
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

    protected $berkasPendukung = [
        ["surat rujukan", "usg"],
        ["laborat"],
    ];

    /**
     * BerkasKlaimController constructor
     */
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
        $bSep              = \App\Models\BridgingSep::with(['pasien', 'reg_periksa', 'dokter.pegawai.sidikjari'])->where('no_sep', $sep)->first();

        $diagnosa          = \App\Models\DiagnosaPasien::with('penyakit')->where('no_rawat', $bSep->no_rawat)->orderBy('prioritas', 'asc')->get();
        $prosedur          = \App\Models\ProsedurPasien::with('penyakit')->where('no_rawat', $bSep->no_rawat)->orderBy('prioritas', 'asc')->get();
        $pasien            = \App\Models\Pasien::where('no_rkm_medis', $bSep->nomr)->first();
        $regPeriksa        = \App\Models\RegPeriksa::where('no_rawat', $bSep->no_rawat)->first();
        $kamarInap         = \App\Models\KamarInap::with('kamar.bangsal')->where('no_rawat', $bSep->no_rawat)->orderBy('tgl_masuk', 'desc')->orderBy('jam_masuk', 'desc')->get();
        $resumePasienRanap = \App\Models\ResumePasienRanap::where('no_rawat', $bSep->no_rawat)->first();
        $spri              = \App\Models\BridgingSuratPriBpjs::where('no_rawat', $bSep->no_rawat)->first();
        
        $lab               = $this->groupPeriksaLabData(
            \App\Models\PeriksaLab::with('pegawai.sidikjari', 'dokter.sidikjari', 'perujuk', 'jenisPerawatan', 'detailPeriksaLab.template')
                ->whereIn('no_rawat', $this->getRegisterLabDouble($regPeriksa->kd_poli, $bSep->no_rawat, $bSep->nomr))->get()
        );

        $berkasPendukung   = \App\Models\RsiaUpload::where('no_rawat', $bSep->no_rawat)->get()->map(function ($item) {
            $item->kategori = strtolower($item->kategori);
            return $item;
        });

        $ttdPasien         = \App\Models\RsiaVerifSep::where('no_sep', $sep)->first();
        $ttdResume         = \App\Models\Pegawai::with(['sidikjari', 'dep'])->whereHas('dep', function ($q) use ($kamarInap) {
            return $q->where('nama', \Illuminate\Support\Str::upper($this->getDepartemen($kamarInap)));
        })->where('status_koor', '1')->first();

        // +==========+==========+==========+==========+==========+==========+==========+==========+

        $berkasSep = $this->generatePdf('berkas-klaim.partials.sep', [
            'sep'      => $bSep,
            'diagnosa' => $diagnosa,
            'prosedur' => $prosedur,
        ]);
        $resumeMedis = $this->generatePdf('berkas-klaim.partials.resume-medis', [
            'sep'        => $bSep->withoutRelations(),
            'pasien'     => $pasien,
            'regPeriksa' => $regPeriksa,
            'kamarInap'  => $kamarInap,
            'resume'     => $resumePasienRanap,
            'ttdResume'  => $ttdResume,
            'ttdDpjp'    => $bSep->dokter->pegawai,
            'ttdPasien'  => $ttdPasien,
        ]);
        $pri = $this->generatePdf('berkas-klaim.partials.spri', [
            'sep'    => $bSep,
            'pasien' => $pasien,
            'spri'   => $spri,
        ]);

        $pendukung0 = [];
        foreach ($this->berkasPendukung[0] as $key => $value) {
            $pendukung0[$value] = $this->generatePdf('berkas-klaim.partials.image', [
                'image' => $berkasPendukung->where('kategori', $value)->first()?->file,
                'alt'   => Str::title($value),
            ]);
        }

        $hasilLab = [];
        foreach ($lab as $key => $value) {
            if ($value->isEmpty()) {
                continue;
            }

            $hasilLab[$key] = $this->generatePdf('berkas-klaim.partials.hasil-lab', [
                'sep'        => $bSep,
                'regPeriksa' => $regPeriksa,
                'lab'        => $value,
            ]);
        }

        $pendukung1 = [];
        foreach ($this->berkasPendukung[1] as $key => $value) {
            $pendukung1[$value] = $this->generatePdf('berkas-klaim.partials.image', [
                'image' => $berkasPendukung->where('kategori', $value)->first()?->file,
                'alt'   => Str::title($value),
            ]);
        }

        $pendukungLainnya = [];
        $mergedPendukungkategori = array_merge($this->berkasPendukung[0], $this->berkasPendukung[1]);
        foreach ($berkasPendukung as $key => $value) {
            if (!in_array($value->kategori, $mergedPendukungkategori)) {
                $pendukungLainnya[$value->kategori] = $this->generatePdf('berkas-klaim.partials.image', [
                    'image' => $value->file,
                    'alt'   => Str::title($value->kategori),
                ]);
            }
        }

        // +==========+==========+==========+==========+==========+==========+==========+==========+

        $this->oMerger->addString($berkasSep->output(), 'all');
        $this->oMerger->addString($resumeMedis->output(), 'all');
        $this->oMerger->addString($pri->output(), 'all');
        
        foreach ($pendukung0 as $key => $value) {
            $this->oMerger->addString($value->output(), 'all');
        }
        
        foreach ($hasilLab as $key => $value) {
            $this->oMerger->addString($value->output(), 'all');
        }
        
        foreach ($pendukung1 as $key => $value) {
            $this->oMerger->addString($value->output(), 'all');
        }

        foreach ($pendukungLainnya as $key => $value) {
            $this->oMerger->addString($value->output(), 'all');
        }

        // +==========+==========+==========+==========+==========+==========+==========+==========+

        $this->oMerger->merge();
        $this->oMerger->setFileName('berkas-klaim-' . $sep . '.pdf');

        // +==========+==========+==========+==========+==========+==========+==========+==========+

        return response($this->oMerger->stream())
            ->header('Content-Type', 'application/pdf')
            ->header('Pragma', 'no-cache')
            ->header('Expires', '0');
    }


    /**
     * Generate PDF
     * 
     * @param string $view
     * @param array $data
     * @return \Barryvdh\DomPDF\PDF
     */
    function generatePdf($view, $data)
    {
        return Pdf::loadView($view, $data)->setPaper($this->f4, $this->orientation);
    }

    /**
     * Get the departemen
     * 
     * @param \Illuminate\Support\Collection $kamarInap
     * @return string|null
     */
    private function getDepartemen($kamarInap)
    {
        if ($kamarInap->isEmpty()) {
            return null;
        }

        $filteredKeys = array_filter(array_keys($this->koorByDepartemen), function ($key) use ($kamarInap) {
            return strpos($kamarInap[0]->kd_kamar, $key) !== false;
        });

        $values = array_values(array_intersect_key($this->koorByDepartemen, array_flip($filteredKeys)));

        return $values[0] ?? null;  // Return the first value or null if empty         
    }

    /**
     * Group periksa lab data
     * 
     * Group the periksa lab data by tgl_periksa and jam
     * 
     * @param \Illuminate\Support\Collection $data
     * @return \Illuminate\Support\Collection
     */
    private function groupPeriksaLabData(Collection $data): Collection
    {
        return $data->groupBy(function ($item) {
            return $item->tgl_periksa . ' ' . $item->jam;
        });
    }

    /**
     * Get the register lab double
     * 
     * @param string $kd_poli
     * @param string $no_rawat
     * @param string $no_rkm_medis
     * @return array
     */
    private function getRegisterLabDouble($kd_poli, $no_rawat, $no_rkm_medis)
    {
        // Filter kd_poli
        $filterPoli = ['U0016', 'OPE'];

        // Check if the kd_poli is in the filter
        if (in_array($kd_poli, $filterPoli)) {
            $registrasiData = \App\Models\RegPeriksa::select('no_rawat', 'no_rkm_medis')
                ->where('no_rkm_medis', $no_rkm_medis)
                ->orderBy('no_rawat', 'desc')->limit(2)->get();

            $noRawat = $registrasiData->pluck('no_rawat')->toArray();

            return $noRawat;
        }

        // Return the no_rawat if not in the filter
        return [$no_rawat];
    }
}
