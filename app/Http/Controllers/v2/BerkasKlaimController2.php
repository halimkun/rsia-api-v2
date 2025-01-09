<?php

namespace App\Http\Controllers\v2;


use App\Helpers\PDFHelper;
use Illuminate\Http\Request;
use App\Helpers\SignHelper;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\App;
use App\Http\Controllers\Controller;

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

        $pendukung   = \App\Models\RsiaUpload::where('no_rawat', $bSep->no_rawat)->get()->map(function ($item) {
            $item->kategori = strtolower($item->kategori);
            return $item;
        });

        $barcodeDPJP = SignHelper::rsia($dpjp->pegawai->nama, $dpjp->pegawai->id);

        $pasien      = $regPeriksa->pasien;

        // ✔ ----- SEP
        // ✔ ----- Triase UGD
        // ✔ ----- Asmed UGD
        // ✔ ----- Resume
        // ✔ ----- CPPT
        // ✔ ----- Operasi
        // ✔ ----- SPRI
        // ✔ ----- Surat Rencana Kontrol
        // ✔ ----- pendukung [skl]
        // ✔ ----- catatan perawatan
        // ✔ ----- pendukung [surat rujukan]
        // ✔ ----- pendukung [usg]
        // ...$this->genHasilLab($bSep, $regPeriksa),
        // ✔ ----- hasil radiologi
        // ✔ ----- pendukung [laborat]
        // ✔ ----- pendukung selain [skl, surat rujukan, usg, lab]
        // billing
        // inacbg klaim 
        // naik kelas

        $pages = collect([
            $this->genSepPage($bSep, $regPeriksa, $pasien, $barcodeDPJP),
            $this->genTriaseUgd($bSep->jnspelayanan, $regPeriksa, $barcodeDPJP),
            $this->genAsmedUgdPage($bSep->jnspelayanan, $regPeriksa, $barcodeDPJP),
            $this->genResumeMedisPage($bSep, $pasien, $regPeriksa, $barcodeDPJP),
            $this->genCpptPage($bSep->jnspelayanan, $regPeriksa, $pasien),
            $this->genOperasiPage($bSep->no_rawat, $regPeriksa, $barcodeDPJP),
            $this->genSpriPage($bSep, $pasien, $barcodeDPJP),
            $this->genRencanaKontrolPage($bSep, $regPeriksa, $pasien, $barcodeDPJP),
            $this->pendukung($pendukung, ['skl']),
            $this->genHasilPemeriksaanUsg($bSep, $regPeriksa, $pasien, $dpjp, $barcodeDPJP),
            $this->pendukung($pendukung, ['surat rujukan']),
            $this->pendukung($pendukung, ['usg']),
            $this->genHasilRadiologiPage($regPeriksa, $pasien, $barcodeDPJP),
            $this->pendukung($pendukung, ['laborat']),
            $this->pendukung($pendukung, ['skl', 'surat rujukan', 'usg', 'laborat'], true),
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

        $barcodePasien = SignHelper::toQr($sep->no_kartu);

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
     * Generate triage UGD view for claim documents.
     *
     * @param int $jenisPelayanan The type of service.
     * @param \App\Models\RegPeriksa $regPeriksa The registration check model.
     * @param \App\Models\BarcodeDPJP $barcodeDPJP The barcode DPJP model.
     * @return string|null The rendered triage UGD view or null if conditions are not met.
     */
    public function genTriaseUgd($jenisPelayanan, $regPeriksa, $barcodeDPJP)
    {
        if ($jenisPelayanan != 1) {
            return null;
        }

        $triase = \App\Models\RsiaTriaseUgd::where('no_rawat', $regPeriksa->no_rawat)->first();

        if ($triase) {
            $triase = view('berkas-klaim.triase', [
                'regPeriksa'  => $regPeriksa,
                'triase'      => $triase,
                'barcodeDPJP' => $barcodeDPJP->getDataUri(),
            ])->render();
    
            return $triase;
        }

    }

    /**
     * Generates the Asmed UGD page for the given service type, registration check, and DPJP barcode.
     *
     * @param int $jenisPelayanan The type of service.
     * @param \App\Models\RegPeriksa $regPeriksa The registration check model.
     * @param \App\Models\BarcodeDPJP $barcodeDPJP The DPJP barcode model.
     * @return string|null The rendered Asmed UGD page or null if the service type is 2.
     */
    public function genAsmedUgdPage($jenisPelayanan, $regPeriksa, $barcodeDPJP)
    {
        if ($jenisPelayanan == 2) {
            return null;
        }

        $asmed = \App\Models\PenilaianMedisIgd::with('dokter.sidikjari')->where('no_rawat', $regPeriksa->no_rawat)->first();

        if ($asmed) {
            $asmed = view('berkas-klaim.asmed-ugd', [
                'regPeriksa'  => $regPeriksa,
                'asmed'       => $asmed,
                'barcodeDPJP' => $barcodeDPJP->getDataUri(),
            ])->render();
    
            return $asmed;
        }
    }

    /**
     * Generate resume medis page for claim documents.
     *
     * @param \App\Models\BridgingSep $sep The SEP (Surat Eligibilitas Peserta) model instance.
     * @param \App\Models\Pasien $pasien The patient model instance.
     * @param \App\Models\RegPeriksa $regPeriksa The registration check model instance.
     * @param \App\Models\BarcodeDPJP $barcodeDPJP The DPJP barcode model instance.
     * @return string|null The rendered resume medis page view or null if no resume data is found.
     */
    public function genResumeMedisPage($sep, $pasien, $regPeriksa, $barcodeDPJP)
    {
        $resume    = \App\Models\ResumePasienRanap::where('no_rawat', $sep->no_rawat)->first();
        if ($resume) {
            $kamarInap = \App\Models\KamarInap::with('kamar.bangsal')->where('no_rawat', $sep->no_rawat)->where('stts_pulang', '<>', 'Pindah Kamar')->orderBy('tgl_masuk', 'desc')->orderBy('jam_masuk', 'desc')->get();
            $koor      = \App\Models\Pegawai::select('id', 'nik', 'nama', 'departemen')->whereHas('dep', function ($q) use ($kamarInap) {
                return $q->where('nama', \Illuminate\Support\Str::upper($this->getDepartemen($kamarInap)));
            })->where('status_koor', '1')->first();
            $barcodeResume = SignHelper::rsia($koor->nama, $koor->id);
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
            ])->render();

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

    /**
     * Generate the USG examination result for the claim document.
     *
     * @param \App\Models\BridgingSep $bSep The SEP (Surat Eligibilitas Peserta) model instance.
     * @param \App\Models\RegPeriksa $regPeriksa The registration check model instance.
     * @param \App\Models\Pasien $pasien The patient model instance.
     * @param \App\Models\Dokter $dpjp The DPJP model instance.
     * @param \Endroid\QrCode\Writer\Result\PngResult $barcodeDPJP The DPJP barcode model instance.
     * 
     * @return string|null The rendered USG examination result view, or null if no USG examination result is found.
     */
    public function genHasilPemeriksaanUsg($bSep, $regPeriksa, $pasien, $dpjp, $barcodeDPJP)
    {
        $catatanPerawatan = \App\Models\CatatanPerawatan::where('no_rawat', $regPeriksa->no_rawat)->first();
        if ($catatanPerawatan) {
            $catatan = view('berkas-klaim.hasil-usg', [
                'sep'        => $bSep,
                'pasien'     => $pasien,
                'regPeriksa' => $regPeriksa,
                'usg'        => $catatanPerawatan,
                'dpjp'       => $dpjp,
                'barcodeDPJP' => $barcodeDPJP->getDataUri(),
            ]);
    
            return $catatan;
        }
    }

    /**
     * Generate the radiology examination result for the claim document.
     *
     * @param \App\Models\BridgingSep $bSep The SEP (Surat Eligibilitas Peserta) model instance.
     * @param \App\Models\RegPeriksa $regPeriksa The registration check model instance.
     * @param \App\Models\Pasien $pasien The patient model instance.
     * @param \App\Models\Dokter $dpjp The DPJP model instance.
     * @param \Endroid\QrCode\Writer\Result\PngResult $barcodeDPJP The DPJP barcode model instance.
     * 
     * @return string|null The rendered radiology examination result view, or null if no radiology examination result is found.
     */
    public function genHasilRadiologiPage($regPeriksa, $pasien)
    {
        $radiologi = \App\Models\PeriksaRadiologi::with(['dokter', 'petugas', 'dokterPerujuk', 'hasilRadiologi', 'jenisPerawatan'])
            ->where('no_rawat', $regPeriksa->no_rawat)->get();
        
        if ($radiologi) {
            $hasilRadiologi = view('berkas-klaim.radiologi', [
                'regPeriksa'  => $regPeriksa,
                'pasien'      => $pasien,
                'radiologi'   => $radiologi,
            ])->render();

            return $hasilRadiologi;
        }
    }

    /**
     * Retrieve and render supporting files based on the given category.
     *
     * @param \Illuminate\Database\Eloquent\Collection|null $data The data collection to search within.
     * @param string $ambil The category to filter the data by.
     * @return string|null The rendered view of supporting files or null if no data or files are found.
     */
    public function pendukung($data, array $ambil, bool $notIn = false)
    {
        if (!$data) {
            return null;
        }

        if ($notIn) {
            $pendukung = $data->whereNotIn('kategori', $ambil)->first();
        } else {
            $pendukung = $data->whereIn('kategori', $ambil)->first();
        }

        if (empty($pendukung)) {
            return null;
        }

        $files = explode(',', $pendukung->file);
        $pendukung = view('berkas-klaim.image', [
            'files' => $files,
        ])->render();

        return $pendukung;
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
