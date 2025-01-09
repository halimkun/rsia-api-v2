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

    /**
     * Koordinat berdasarkan departemen
     *
     * @var array
     */
    protected $koorByDepartemen = [
        'Anak'      => 'Anak',
        'Kandungan' => 'Nifas',
        'BY'        => 'PERINATOLOGI',
        'VK'        => 'VK',
    ];

    
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

    private function barcodeText($name, $id_or_nik)
    {
        $hash = \App\Models\SidikJari::where('id', $id_or_nik)->select('id', DB::raw('SHA1(sidikjari) as sidikjari'))->first();

        if ($hash) {
            $hash = $hash->sidikjari;
        } else {
            $hash = Hash::make($id_or_nik);
        }

        $text     = 'Dikeluarkan di RSIA Aisyiyah Pekajangan, Ditandatangani secara elektronik oleh ' . $name . '. ID : ' . $hash;
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

        return $values[0] ?? null; // Return the first value or null if empty
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
        // ✔ ----- CPPT
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
            $this->genResumeMedisPage($bSep, $pasien, $regPeriksa, $barcodeDPJP),
            $this->genCpptPage($bSep->jnspelayanan, $regPeriksa, $pasien),
            $this->genOperasiPage($bSep->no_rawat, $regPeriksa, $barcodeDPJP),
            $this->genSpriPage($bSep, $pasien, $barcodeDPJP),
            $this->genRencanaKontrolPage($bSep, $regPeriksa, $pasien, $barcodeDPJP),
        ]);

        // map pages where not null
        $pages = $pages->filter(function ($page) {
            return !empty($page);
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

    public function genResumeMedisPage($sep, $pasien, $regPeriksa, $barcodeDPJP)
    {
        $resume    = \App\Models\ResumePasienRanap::where('no_rawat', $sep->no_rawat)->first();
        if ($resume) {
            $kamarInap = \App\Models\KamarInap::with('kamar.bangsal')->where('no_rawat', $sep->no_rawat)->where('stts_pulang', '<>', 'Pindah Kamar')->orderBy('tgl_masuk', 'desc')->orderBy('jam_masuk', 'desc')->get();
            $koor      = \App\Models\Pegawai::select('id', 'nik', 'nama', 'departemen')->whereHas('dep', function ($q) use ($kamarInap) {
                return $q->where('nama', \Illuminate\Support\Str::upper($this->getDepartemen($kamarInap)));
            })->where('status_koor', '1')->first();
            $barcodeResume = $this->barcodeText($koor->nama, $koor->id);
            $ttdPasien     = \App\Models\RsiaVerifSep::where('no_sep', $sep->no_sep)->first();
    
            $resumeMedis = view('berkas-klaim.resume', [
                'sep'         => $sep,
                'pasien'      => $pasien,
                'regPeriksa'  => $regPeriksa,
                'kamarInap'   => $kamarInap,
                'resume'      => $resume,
                'koor'        => $koor,
                'barcodeKoor' => $barcodeResume->getDataUri(),
                'barcodeDPJP' => $barcodeDPJP->getDataUri(),
                'ttdPasien'   => $ttdPasien,
            ]);
    
            return $resumeMedis;
        }

    }

    /**
     * Generate CPPT (Catatan Perkembangan Pasien Terintegrasi) document.
     *
     * @param string $jenisPelayanan The type of service.
     * @param \App\Models\RegPeriksa $regPeriksa The registration check model instance.
     * @param \App\Models\Pasien $pasien The patient model instance.
     * 
     * @return string|null The rendered CPPT document view, or null if no corresponding CPPT is found.
     */
    public function genCpptPage(string $jenisPelayanan, $regPeriksa, $pasien)
    {
        $no_rawat = \App\Models\RegPeriksa::select('no_rawat')
            ->where('no_rkm_medis', $regPeriksa->no_rkm_medis)
            ->whereBetween('tgl_registrasi', [\Carbon\Carbon::parse($regPeriksa->tgl_registrasi)->subDays(30), \Carbon\Carbon::parse($regPeriksa->tgl_registrasi)->addDays(30)])
            ->orderBy('tgl_registrasi', 'desc')
            ->get();

        $no_rawat = $no_rawat->pluck('no_rawat')->toArray();

        if ($jenisPelayanan == "2") {
            $cppt = \App\Models\PemeriksaanRalanKlaim::with('petugas')->whereIn('no_rawat', $no_rawat)->orderBy('tgl_perawatan', 'DESC')->get();
        } else if ($jenisPelayanan == "1") {
            $cppt = \App\Models\PemeriksaanRanapKlaim::with('petugas')->whereIn('no_rawat', $no_rawat)->orderBy('tgl_perawatan', 'DESC')->get();
        } else {
            return null;
        }

        if (!$cppt || $cppt->isEmpty()) {
            return null;
        }

        $cppt = view('berkas-klaim.cppt', [
            'regPeriksa' => $regPeriksa->withoutRelations(),
            'pasien'     => $pasien,
            'cppt'       => $cppt,
        ])->render();

        return $cppt;
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
        if ($spri && $spri->no_surat) {
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
