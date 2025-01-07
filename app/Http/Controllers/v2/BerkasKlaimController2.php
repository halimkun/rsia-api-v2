<?php

namespace App\Http\Controllers\v2;


use App\Helpers\PDFHelper;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class BerkasKlaimController2 extends Controller
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


    private function toBarcode($data)
    {
        $qrCode = \Endroid\QrCode\Builder\Builder::create()
            ->writer(new \Endroid\QrCode\Writer\PngWriter())
            ->writerOptions([])
            ->data($data)
            ->encoding(new \Endroid\QrCode\Encoding\Encoding('ISO-8859-1'))
            ->errorCorrectionLevel(new \Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelHigh())
            ->build();

        return $qrCode;
    }

    private function barcodeText($dpjp, $id_or_nik)
    {
        $hash = \App\Models\SidikJari::where('id', $id_or_nik)->select('id', DB::raw('SHA1(sidikjari) as sidikjari'))->first();

        if ($hash) {
            $hash = $hash->sidikjari;
        } else {
            $hash = Hash::make($id_or_nik);
        }

        $text     = 'Dikeluarkan di RSIA Aisyiyah Pekajangan, Ditandatangani secara elektronik oleh ' . $dpjp . '. ID : ' . $hash;
        $logoPath = asset('assets/images/logo.png');

        $qrCode = \Endroid\QrCode\Builder\Builder::create()
            ->writer(new \Endroid\QrCode\Writer\PngWriter())
            ->writerOptions([])
            ->data($text)
            ->logoPath($logoPath)
            ->logoResizeToWidth(100)
            ->encoding(new \Endroid\QrCode\Encoding\Encoding('ISO-8859-1'))
            ->errorCorrectionLevel(new \Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelHigh())
            ->build();


        return $qrCode;
    }




    public function print($sep, Request $request)
    {
        $bSep        = \App\Models\BridgingSep::where('no_sep', $sep)->first();
        $regPeriksa  = \App\Models\RegPeriksa::with(['pasien'])->where('no_rawat', $bSep->no_rawat)->first();
        $dpjp        = \App\Models\Dokter::with('pegawai')->where('kd_dokter', $regPeriksa->kd_dokter)->first();
        
        $barcodeDPJP = $this->barcodeText($dpjp->pegawai->nama, $dpjp->pegawai->id);

        $pasien      = $regPeriksa->pasien;

        // ✔ ----- SEP
        // Triase UGD
        // Asmed UGD
        // Resume
        // CPPT
        // ✔ ----- Operasi
        // ✔ ----- SPRI
        // ✔ ----- Surat Rencana Kontrol
        // pendukung [skl]
        // catatan perawatan
        // pendukung [surat rujukan]
        // pendukung [usg]
        // ...$this->genHasilLab($bSep, $regPeriksa),
        // hasil radiologi
        // pendukung [laborat]
        // pendukung selain [skl, surat rujukan, usg, lab]
        // billing
        // inacbg klaim 
        // naik kelas

        $pages = collect([
            $this->genSepPage($bSep, $regPeriksa, $pasien, $barcodeDPJP),
            $this->genOperasiPage($bSep->no_rawat, $regPeriksa, $barcodeDPJP),
            $this->genSpriPage($bSep, $pasien, $barcodeDPJP),
            $this->genRencanaKontrolPage($bSep, $regPeriksa, $pasien, $barcodeDPJP),
        ]);

        // map pages where not null
        $pages = $pages->filter(function ($page) {
            return $page !== null;
        });

        $html = PDFHelper::generate('berkas-klaim.layout', [
            'pages' => $pages
        ], false);

        return response($html->stream('berkas-klaim-' . $sep . '.pdf'), 200)
            ->header('Content-Type', 'application/pdf')
            ->header('Pragma', 'no-cache')
            ->header('Expires', '0');
    }




    /**
     * Generate SEP (Surat Eligibilitas Peserta) document.
     *
     * @param \App\Models\BridgingSep $sep The SEP (Surat Eligibilitas Peserta) model instance.
     * @param \App\Models\RegPeriksa $regPeriksa The registration check model instance.
     * @param \App\Models\Pasien $pasien The patient model instance.
     * @param \Endroid\QrCode\Writer\Result\PngResult $barcodeDPJP The DPJP barcode.
     * 
     * @return string Rendered SEP document view.
     */
    public function genSepPage($sep, $regPeriksa, $pasien, $barcodeDPJP)
    {
        $diagnosa = \App\Models\DiagnosaPasien::with('penyakit')->orderBy('prioritas', 'asc')->where('no_rawat', $regPeriksa->no_rawat)->get();
        $prosedur = \App\Models\ProsedurPasien::with('penyakit')->orderBy('prioritas', 'asc')->where('no_rawat', $regPeriksa->no_rawat)->get();
        
        $barcodePasien = $this->toBarcode($sep->no_kartu);

        $berkasSep = view('berkas-klaim.sep', [
            'sep'           => $sep,
            'pasien'        => $pasien,
            'regPeriksa'    => $regPeriksa,
            'diagnosa'      => $diagnosa,
            'prosedur'      => $prosedur,
            'barcodeDPJP'   => $barcodeDPJP->getDataUri(),
            'barcodePasien' => $barcodePasien->getDataUri(),
        ]);

        return $berkasSep->render();
    }

    /**
     * Generate Surat Perintah Rawat Inap document.
     *
     * @param \App\Models\BridgingSep $sep The SEP (Surat Eligibilitas Peserta) model instance.
     * @param \App\Models\Pasien $pasien The patient model instance.
     * @param \Endroid\QrCode\Writer\Result\PngResult $barcodeDPJP The DPJP barcode.
     * 
     * @return string|null The rendered control plan letter view, or null if no corresponding Surat Kontrol is found
     */
    public function genSpriPage($sep, $pasien, $barcodeDPJP)
    {
        $spri = \App\Models\BridgingSuratPriBpjs::where('no_surat', $sep->noskdp)->first();
        if ($spri) {
            $spri = view('berkas-klaim.spri', [
                'sep'           => (object) $sep->only(['tglsep', 'no_sep', 'no_kartu']),
                'pasien'        => $pasien,
                'spri'          => $spri,
                'barcodeDPJP'   => $barcodeDPJP->getDataUri(),
            ]);

            return $spri->render();
        }
    }

    /**
     * Generates a control plan letter (Surat Rencana Kontrol) for a patient.
     *
     * @param \App\Models\BridgingSep $sep The SEP (Health Insurance Participant Eligibility Letter) information.
     * @param \App\Models\RegPeriksa $regPeriksa The registration check information.
     * @param \App\Models\Pasien $pasien The patient information.
     * @param \Endroid\QrCode\Writer\Result\PngResult $barcodeDPJP The DPJP barcode.
     * 
     * @return string|null The rendered control plan letter view, or null if no corresponding Surat Kontrol is found.
     */
    public function genRencanaKontrolPage($sep, $regPeriksa, $pasien, $barcodeDPJP)
    {
        $srk = \App\Models\BridgingSuratKontrolBpjs::where('no_surat', $sep->noskdp)->first();
        if ($srk) {
            $kontrol = view('berkas-klaim.kontrol', [
                'sep'         => $sep,
                'pasien'      => $pasien,
                'regPeriksa'  => $regPeriksa,
                'srk'         => $srk,
                'barcodeDPJP' => $barcodeDPJP->getDataUri()
            ])->render();

            return $kontrol;
        }
    }

    /**
     * Generate Operasi document.
     *
     * @param string $no_rawat The registration number.
     * @param \App\Models\RegPeriksa $regPeriksa The registration check model instance.
     * @param \Endroid\QrCode\Writer\Result\PngResult $barcodeDPJP The DPJP barcode.
     * 
     * @return string|null The rendered operation document view, or null if no corresponding operation data is found.
     */
    public function genOperasiPage(string $no_rawat, $regPeriksa, $barcodeDPJP)
    {
        $operasiData = \App\Models\RsiaOperasiSafe::withAllRelations()->where('no_rawat', $no_rawat)->get();
        if ($operasiData) {
            $operasi = view('berkas-klaim.operasi', [
                'data'        => $operasiData,
                'regPeriksa'  => $regPeriksa,
                'barcodeDPJP' => $barcodeDPJP->getDataUri()
            ])->render();

            return $operasi;
        }
    }

    public function genHasilLab($sep, $regPeriksa)
    {
        $labWih  = ['perujuk', 'jenisPerawatan', 'detailPeriksaLab.template'];
        $labData = \App\Models\PeriksaLab::with($labWih)->whereIn('no_rawat', $this->getRegisterLabDouble($regPeriksa->kd_poli, $sep->no_rawat, $sep->nomr))->orderBy('tgl_periksa', 'DESC')->orderBy('jam', 'DESC')->get();
        $lab     = $this->groupPeriksaLabData($labData);

        $hasilLab = [];

        foreach ($lab as $key => $value) {
            if ($value->isEmpty()) {
                continue;
            }

            $hasilLab[$key] = PDFHelper::generate('berkas-klaim.hasil-lab', [
                'sep'        => $sep,
                'regPeriksa' => $regPeriksa,
                'lab'        => $value,
            ]);
        }

        return $hasilLab;
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
            $registrasiData = \App\Models\RegPeriksa::where('no_rkm_medis', $no_rkm_medis)
                ->where('no_rawat', '<=', $no_rawat)
                ->orderBy('no_rawat', 'desc')->limit(2)
                ->pluck('no_rawat');

            return $registrasiData->toArray();
        }

        // Return the no_rawat if not in the filter
        return [$no_rawat];
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
}
