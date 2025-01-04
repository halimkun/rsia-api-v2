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
        'VK'        => 'VK',
    ];

    /**
     * PDF Merger
     *
     * @var \Webklex\PDFMerger\PDFMerger
     */
    protected $berkasPendukung = [
        ["skl"],
        ["surat rujukan"],
        ["usg"],
        ["laborat"],
    ];

    public function export($sep)
    {
        \App\Jobs\ExportPdfJob::dispatch($sep)->delay(now()->addSeconds(5));

        sleep(3);

        return response()->json([
            'message' => 'berkas klaim akan segera di export.',
        ]);
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
        $params = $request->query();

        $bSep              = \App\Models\BridgingSep::with(['pasien', 'reg_periksa', 'dokter.pegawai.sidikjari', 'surat_kontrol', 'naikKelas'])->where('no_sep', $sep)->first();
        $regPeriksa        = \App\Models\RegPeriksa::with(['pasien', 'poliklinik', 'diagnosaPasien.penyakit', 'prosedurPasien.penyakit', 'catatanPerawatan', 'caraBayar'])->where('no_rawat', $bSep->no_rawat)->first();
        $kamarInap         = \App\Models\KamarInap::with('kamar.bangsal')->where('no_rawat', $bSep->no_rawat)->where('stts_pulang', '<>', 'Pindah Kamar')->orderBy('tgl_masuk', 'desc')->orderBy('jam_masuk', 'desc')->get();
        $operasi           = \App\Models\RsiaOperasiSafe::withAllRelations()->where('no_rawat', $bSep->no_rawat)->get();
        $resumePasienRanap = \App\Models\ResumePasienRanap::where('no_rawat', $bSep->no_rawat)->first();
        $obat              = $this->groupDetailPemberianObat(\App\Models\DetailPemberianObat::select('tgl_perawatan', 'jam', 'no_rawat', 'kode_brng', 'jml')->with('obat')->whereIn('no_rawat', $this->cekGabung($bSep->no_rawat))->get());

        $radiologiSelect = ['no_rawat', 'nip', 'kd_jenis_prw', 'tgl_periksa', 'jam', 'dokter_perujuk', 'kd_dokter', 'status'];
        $radiologiWith   = ['dokter.sidikjari', 'dokterPerujuk', 'hasilRadiologi', 'jenisPerawatan', 'petugas.sidikjari'];
        $radiologi       = \App\Models\PeriksaRadiologi::select($radiologiSelect)->with($radiologiWith)->where('no_rawat', $bSep->no_rawat)->orderBy('tgl_periksa', 'ASC')->orderBy('jam', 'ASC')->get();

        $berkasPendukung = \App\Models\RsiaUpload::where('no_rawat', $bSep->no_rawat)->get()->map(function ($item) {
            $item->kategori = strtolower($item->kategori);
            return $item;
        });

        $ttdPasien = \App\Models\RsiaVerifSep::where('no_sep', $sep)->first();
        $ttdResume = \App\Models\Pegawai::with(['sidikjari', 'dep'])->whereHas('dep', function ($q) use ($kamarInap) {
            return $q->where('nama', \Illuminate\Support\Str::upper($this->getDepartemen($kamarInap)));
        })->where('status_koor', '1')->first();

        // +==========+==========+==========+

        $pdfs = [
            $this->genSep($bSep, $regPeriksa->diagnosaPasien, $regPeriksa->prosedurPasien),
            $this->genTriaseUgd($regPeriksa, $bSep),
            $this->genAsmedUgd($regPeriksa, $bSep),
        ];

        if ($resumePasienRanap) {
            $pdfs[] = $this->genResumeMedis($bSep, $regPeriksa->pasien, $regPeriksa, $kamarInap, $resumePasienRanap, $ttdResume, $bSep->dokter->pegawai, $ttdPasien);
        }

        $pdfs[] = $this->genCppt($bSep, $regPeriksa);

        if ($operasi) {
            $pdfs = array_merge($pdfs, $this->genLaporanOperasi($regPeriksa, $operasi));
        }

        $pdfs[] = $this->genSuratPerintahRawatInap($bSep, $regPeriksa->pasien);

        if ($bSep->surat_kontrol) {
            $pdfs[] = $this->genSuratRencanaKontrol($bSep, $regPeriksa);
        }

        if ($berkasPendukung->where('kategori', 'skl')->first()) {
            $pdfs = array_merge($pdfs, $this->genBerkasPendukung(['skl'], $berkasPendukung));
        }

        if ($regPeriksa->catatanPerawatan) {
            $pdfs[] = $this->genHasilPemeriksaanUsg($bSep, $regPeriksa->pasien, $regPeriksa);
        }

        if ($berkasPendukung->where('kategori', 'surat rujukan')->first()) {
            $pdfs = array_merge($pdfs, $this->genBerkasPendukung(['surat rujukan'], $berkasPendukung));
        }

        if ($berkasPendukung->where('kategori', 'usg')->first()) {
            $pdfs = array_merge($pdfs, $this->genBerkasPendukung(['usg'], $berkasPendukung));
        }

        $hasilLab = $this->genHasilLab($bSep, $regPeriksa);
        if (!empty($hasilLab)) {
            $pdfs = array_merge($pdfs, $this->genHasilLab($bSep, $regPeriksa));
        }

        $hasilRadiologi = $this->genHasilPeriksaRadiologi($regPeriksa, $radiologi);
        if (!empty($hasilRadiologi)) {
            $pdfs = array_merge($pdfs, $hasilRadiologi);
        }

        if ($berkasPendukung->where('kategori', 'laborat')->first()) {
            $pdfs = array_merge($pdfs, $this->genBerkasPendukung(['laborat'], $berkasPendukung));
        }

        if ($berkasPendukung->whereNotIn('kategori', ['skl', 'surat rujukan', 'usg', 'laborat'])->first()) {
            $pdfs = array_merge($pdfs, $this->genBerkasPendukung($berkasPendukung->whereNotIn('kategori', ['skl', 'surat rujukan', 'usg', 'laborat'])->pluck('kategori')->toArray(), $berkasPendukung));
        }

        $pdfs = array_merge($pdfs, $this->genDetailObat($obat, $regPeriksa));

        // Billing Detail
        $pdfs[] = $this->genBillingDetail($bSep->no_rawat, $regPeriksa);

        // INACBG's klaim report
        $pdfs[] = $this->genInacbgReport($sep);

        // Kwitansi naik kelas
        $pdfs[] = $this->genKwitansiNaikKelas($bSep, $kamarInap, $ttdPasien);

        $pdf = PDFHelper::merge($pdfs);

        // +==========+==========+==========+

        $pdf->setFileName('berkas-klaim-' . $sep . '.pdf');

        // +==========+==========+==========+

        // if action in query and action is export
        if (isset($params['action']) && $params['action'] == 'export') {
            return $pdf->output();
        }

        return response($pdf->stream(), 200)
            ->header('Content-Type', 'application/pdf')
            ->header('Pragma', 'no-cache')
            ->header('Expires', '0');
    }

    public function genBillingDetail(string $no_rawat, $regPeriksa)
    {
        $billing = $this->billingData($no_rawat);

        if (Str::lower($regPeriksa->status_lanjut) == 'ranap') {
            $resepPulang = \App\Models\ResepPulang::with('obat')->where('no_rawat', $no_rawat)->get()->groupBy('kode_brng');
            $ruang       = \App\Models\KamarInap::with('kamar')->where('no_rawat', $no_rawat)->orderBy('tgl_masuk', 'desc')->orderBy('jam_masuk', 'desc')->get();
        } else {
            $resepPulang = null;
            $ruang       = null;
        }

        $tambahanBiaya = \App\Models\TambahanBiaya::where('no_rawat', $no_rawat)->orderBy('nama_biaya', 'desc')->get();
        $potonganBiaya = \App\Models\PenguranganBiaya::where('no_rawat', $no_rawat)->orderBy('nama_pengurangan', 'desc')->get();
        $returObat     = \App\Models\DetReturJual::with('obat')->where('no_retur_jual', 'like', "%$no_rawat%")->get()->groupBy("kode_brng");
        $kasir         = \App\Helpers\JurnalHelper::determinePetugas($no_rawat);
        $asmenKeuangan = \App\Models\Pegawai::select('id', 'nik', 'nama', 'jnj_jabatan')->with('sidikjari')->where('jnj_jabatan', 'RS7')->first();

        $dokters = null;
        $nota    = null;

        if (Str::lower($regPeriksa->status_lanjut) == 'ralan') {
            $nota = \App\Models\NotaJalan::where('no_rawat', $no_rawat)->first();
        } else if (Str::lower($regPeriksa->status_lanjut) == 'ranap') {
            $dokters = \App\Models\RawatInapDr::with('dokter.spesialis')->where('no_rawat', $no_rawat)->get()->groupBy('kd_dokter');
            $nota    = \App\Models\NotaInap::where('no_rawat', $no_rawat)->first();
        }

        // if dokter not null, sort by nm_dokter
        if ($dokters) {
            $dokters = $dokters->sortBy('dokter.nm_dokter');
        }

        $page = PDFHelper::generate('berkas-klaim.partials.billing', [
            'regPeriksa'    => $regPeriksa,
            'dokters'       => $dokters,
            'ruang'         => $ruang,
            'billing'       => $billing,
            'nota'          => $nota,
            'resepPulang'   => $resepPulang ?? null,
            'tambahanBiaya' => $tambahanBiaya,
            'potonganBiaya' => $potonganBiaya,
            'returObat'     => $returObat,
            'kasir'         => $kasir,
            'asmenKeuangan' => $asmenKeuangan,
        ]);

        return $page;
    }

    /**
     * Generate INACBG report
     *
     * @param string $sep
     * @return string
     */
    public function genInacbgReport($sep)
    {
        \Halim\EKlaim\Builders\BodyBuilder::setMetadata('claim_print');
        \Halim\EKlaim\Builders\BodyBuilder::setData([
            "nomor_sep" => $sep,
        ]);

        $response = \Halim\EKlaim\Services\EklaimService::send(\Halim\EKlaim\Builders\BodyBuilder::prepared());

        if ($response->getStatusCode() == 200) {
            $resp = $response->getData();

            if (!$resp->data) {
                Log::channel(config('eklaim.log_channel'))->error('Berkas Klaim Print - Failed to generate INACBG report', [
                    'sep'      => $sep,
                    'response' => $resp,
                ]);

                return null;
            }

            return base64_decode($resp->data);
        }

        Log::channel(config('eklaim.log_channel'))->error('Berkas Klaim Print - Failed to generate INACBG report', [
            'sep'      => $sep,
            'response' => $response->getData(),
        ]);

        return null;
    }

    public function genCppt($sep, $regPeriksa)
    {
        $no_rawat = \App\Models\RegPeriksa::select('no_rawat')
            ->where('no_rkm_medis', $regPeriksa->no_rkm_medis)
            ->whereBetween('tgl_registrasi', [\Carbon\Carbon::parse($regPeriksa->tgl_registrasi)->subDays(30), \Carbon\Carbon::parse($regPeriksa->tgl_registrasi)->addDays(30)])
            ->orderBy('tgl_registrasi', 'desc')
            ->get();

        $no_rawat = $no_rawat->pluck('no_rawat')->toArray();

        if ($sep->jnspelayanan == 2) {
            $cppt = \App\Models\PemeriksaanRalanKlaim::with('petugas')->whereIn('no_rawat', $no_rawat)->orderBy('tgl_perawatan', 'DESC')->get();
        } else if ($sep->jnspelayanan == 1) {
            $cppt = \App\Models\PemeriksaanRanapKlaim::with('petugas')->whereIn('no_rawat', $no_rawat)->orderBy('tgl_perawatan', 'DESC')->get();
        } else {
            return null;
        }

        if (!$cppt || $cppt->isEmpty()) {
            return null;
        }

        $pasien = \App\Models\Pasien::where('no_rkm_medis', $regPeriksa->no_rkm_medis)->first();

        $cppFile = PDFHelper::generate('berkas-klaim.partials.cppt', [
            'regPeriksa' => $regPeriksa->withoutRelations(),
            'pasien'     => $pasien,
            'cppt'       => $cppt,
        ]);

        return $cppFile;
    }

    public function genKwitansiNaikKelas($sep, $kamarInap, $ttdPasien)
    {
        if (!$sep->naikKelas) {
            return null;
        }

        $kasir = \App\Helpers\JurnalHelper::determinePetugas($sep->no_rawat);

        if (!$kasir) {
            $kasir = \App\Models\Pegawai::with('sidikjari')->where('jnj_jabatan', 'RS7')->first();

            Log::channel('eklaim')->error('Berkas Klaim Print - Failed to determine kasir using jurnal logs', [
                'sep' => $sep,
            ]);
        }

        $kwitansi = PDFHelper::generate('berkas-klaim.partials.kwitansi-naik-kelas', [
            'sep'       => $sep,
            'kamarInap' => $kamarInap,
            'ttdPasien' => $ttdPasien,
            'kasir'     => $kasir,
        ]);

        return $kwitansi;
    }

    public function genAsmedUgd($regPeriksa, $bSep)
    {
        if ($bSep->jnspelayanan != 2) {
            return null;
        }

        $asmed = \App\Models\PenilaianMedisIgd::with('dokter.sidikjari')->where('no_rawat', $regPeriksa->no_rawat)->first();

        if (!$asmed) {
            return null;
        }

        $asmedUgd = PDFHelper::generate('berkas-klaim.partials.asmed-ugd', [
            'regPeriksa' => $regPeriksa,
            'asmed'      => $asmed,
        ]);

        return $asmedUgd;
    }

    public function genTriaseUgd($regPeriksa, $bSep)
    {
        if ($bSep->jnspelayanan != 2) {
            return null;
        }

        $triase = \App\Models\RsiaTriaseUgd::where('no_rawat', $regPeriksa->no_rawat)->first();

        if (!$triase) {
            return null;
        }

        $triaseUgd = PDFHelper::generate('berkas-klaim.partials.triase', [
            'regPeriksa' => $regPeriksa,
            'triase'     => $triase,
        ]);

        return $triaseUgd;
    }

    /**
     * Generate SEP PDF
     *
     * @param \App\Models\BridgingSep $brigdingSep
     * @param \Illuminate\Support\Collection $diagnosa
     * @param \Illuminate\Support\Collection $prosedur
     *
     * @return \Barryvdh\DomPDF\PDF
     */
    public function genSep($brigdingSep, $diagnosa, $prosedur)
    {
        $berkasSep = PDFHelper::generate('berkas-klaim.partials.sep', [
            'sep'      => $brigdingSep,
            'diagnosa' => $diagnosa,
            'prosedur' => $prosedur,
        ]);

        return $berkasSep;
    }

    /**
     * Generate Resume Medis PDF
     *
     * @param \App\Models\BridgingSep $brigdingSep
     * @param \App\Models\Pasien $pasien
     * @param \App\Models\RegPeriksa $regPeriksa
     * @param \Illuminate\Support\Collection $kamarInap
     * @param \App\Models\ResumePasienRanap $resume
     * @param \App\Models\Pegawai $ttdResume
     * @param \App\Models\Pegawai $ttdDpjp
     * @param \App\Models\RsiaVerifSep $ttdPasien
     *
     * @return \Barryvdh\DomPDF\PDF
     */
    public function genResumeMedis($brigdingSep, $pasien, $regPeriksa, $kamarInap, $resume, $ttdResume, $ttdDpjp, $ttdPasien)
    {
        $resumeMedis = PDFHelper::generate('berkas-klaim.partials.resume-medis', [
            'sep'        => $brigdingSep->withoutRelations(),
            'pasien'     => $pasien,
            'regPeriksa' => $regPeriksa,
            'kamarInap'  => $kamarInap,
            'resume'     => $resume,
            'ttdResume'  => $ttdResume,
            'ttdDpjp'    => $ttdDpjp,
            'ttdPasien'  => $ttdPasien,
        ]);

        return $resumeMedis;
    }

    public function genLaporanOperasi($regPeriksa, $operasi)
    {
        $operasiHtml = [];

        foreach ($operasi as $key => $value) {
            $operasiHtml[] = PDFHelper::generate('berkas-klaim.partials.laporan-operasi', [
                'regPeriksa' => $regPeriksa,
                'operasi'    => $value,
            ]);
        }

        return $operasiHtml;
    }

    public function genHasilPemeriksaanUsg($brigdingSep, $pasien, $regPeriksa)
    {
        $resumeMedis = PDFHelper::generate('berkas-klaim.partials.hasil-usg', [
            'sep'        => $brigdingSep->withoutRelations(),
            'pasien'     => $pasien,
            'regPeriksa' => $regPeriksa->withoutRelations(),
            'usg'        => $regPeriksa->catatanPerawatan,
        ]);

        return $resumeMedis;
    }

    /**
     * Generate Surat Perintah Rawat Inap PDF
     *
     * @param \App\Models\BridgingSep $brigdingSep
     * @param \App\Models\Pasien $pasien
     * @param \App\Models\BridgingSuratPriBpjs $spri
     *
     * @return \Barryvdh\DomPDF\PDF
     */
    public function genSuratPerintahRawatInap($brigdingSep, $pasien)
    {
        if (!$brigdingSep->noskdp) {
            return null;
        }

        $spri = \App\Models\BridgingSuratPriBpjs::where('no_surat', $brigdingSep->noskdp)->first();

        if (!$spri) {
            return null;
        }

        $pri = PDFHelper::generate('berkas-klaim.partials.spri', [
            'sep'    => $brigdingSep,
            'pasien' => $pasien,
            'spri'   => $spri,
        ]);

        return $pri;
    }

    public function genSuratRencanaKontrol($sep, $regPeriksa)
    {
        $kontrol = PDFHelper::generate('berkas-klaim.partials.kontrol', [
            'sep'        => $sep,
            'regPeriksa' => $regPeriksa,
        ]);

        return $kontrol;
    }

    /**
     * Generate Hasil Lab PDF
     *
     * @param \App\Models\BridgingSep $sep
     * @param \App\Models\RegPeriksa $regPeriksa
     * @param \Illuminate\Support\Collection $lab
     *
     * @return array
     */
    public function genHasilLab($sep, $regPeriksa)
    {
        $labWih  = ['pegawai.sidikjari', 'dokter.sidikjari', 'perujuk', 'jenisPerawatan', 'detailPeriksaLab.template'];
        $labData = \App\Models\PeriksaLab::with($labWih)->whereIn('no_rawat', $this->getRegisterLabDouble($regPeriksa->kd_poli, $sep->no_rawat, $sep->nomr))->orderBy('tgl_periksa', 'DESC')->orderBy('jam', 'DESC')->get();
        $lab     = $this->groupPeriksaLabData($labData);

        $hasilLab = [];

        foreach ($lab as $key => $value) {
            if ($value->isEmpty()) {
                continue;
            }

            $hasilLab[$key] = PDFHelper::generate('berkas-klaim.partials.hasil-lab', [
                'sep'        => $sep,
                'regPeriksa' => $regPeriksa,
                'lab'        => $value,
            ]);
        }

        return $hasilLab;
    }

    /**
     * Generate Hasil Lab PDF
     *
     * @param \App\Models\BridgingSep $sep
     * @param \App\Models\RegPeriksa $regPeriksa
     * @param \Illuminate\Support\Collection $lab
     *
     * @return array
     */
    public function genHasilPeriksaRadiologi($regPeriksa, $radiologi)
    {
        $hasilRadiologi = [];

        foreach ($radiologi as $key => $value) {
            if (!$value) {
                continue;
            }

            $hasilRadiologi[$key] = PDFHelper::generate('berkas-klaim.partials.radiologi', [
                'regPeriksa' => $regPeriksa,
                'radiologi'  => $value,
            ]);
        }

        return $hasilRadiologi;
    }

    public function genDetailObat($obat, $regPeriksa)
    {
        $tempPdf    = [];
        $detailObat = [];

        $regPeriksa = $regPeriksa->whereIn('no_rawat', array_keys($obat->toArray()))->get()->keyBy('no_rawat');

        foreach ($obat as $key => $value) {
            foreach ($value as $sk => $sv) {
                // table obat
                $detailObat[$key] = \Illuminate\Support\Facades\View::make('berkas-klaim.partials.obat', [
                    'obat' => $value,
                ]);
            }
        }

        foreach ($detailObat as $key => $value) {
            $mpdf = new \Mpdf\Mpdf([
                'format'  => [215, 330],
                'tempDir' => storage_path('app/public/mpdf'),
            ]);

            $mpdf->SetColumns(1, 'J');

            // html tag to head
            $mpdf->WriteHTML(\Illuminate\Support\Facades\View::make('berkas-klaim.partials.header.obat-html', [
                'regPeriksa' => $regPeriksa->get($key),
            ]));

            // header <header></header>
            $mpdf->WriteHTML(\Illuminate\Support\Facades\View::make('berkas-klaim.partials.header.obat-header', [
                'regPeriksa' => $regPeriksa->get($key),
            ]));

            $mpdf->SetColumns(2, 'J', 3);
            $mpdf->WriteHTML($value);

            if (count($mpdf->ColDetails) % 2 != 0) {
                $mpdf->AddColumn();
            }

            // footer <footer></footer>
            $mpdf->WriteHTML(\Illuminate\Support\Facades\View::make('berkas-klaim.partials.footer.obat-footer'));

            $tempPdf[] = $mpdf->Output('obat.pdf', 'S');
        }

        return $tempPdf;
    }

    /**
     * Generate Berkas Pendukung PDF
     *
     * @param array $kategori
     * @param \Illuminate\Support\Collection $berkasPendukung
     * @param bool $notInKategori
     *
     * @return array
     */
    public function genBerkasPendukung(array $kategori, $berkasPendukung)
    {
        $pendukung = [];

        foreach ($kategori as $key => $value) {
            if (!$berkasPendukung->where('kategori', $value)->first()) {
                continue;
            }

            $file  = $berkasPendukung->where('kategori', $value)->first()->file;
            $files = explode(',', $file);

            foreach ($files as $file) {
                $pendukung[] = PDFHelper::generate('berkas-klaim.partials.image', [
                    'image' => $file,
                    'alt'   => Str::title($value),
                ]);
            }
        }

        return $pendukung;
    }

    // +==========+==========+==========+==========+==========+==========+==========+==========+==========+

    /**
     * Cek gabung
     *
     * Cek pasien rawat gabung atau tidak
     *
     * @param string $no_rawat
     * @return array
     */
    private function cekGabung($no_rawat)
    {
        $ranapGabung = \App\Models\RanapGabung::where('no_rawat', $no_rawat)->first();

        if ($ranapGabung) {
            $ranapGabung = array_values($ranapGabung->toArray());
            return $ranapGabung;
        }

        return [$no_rawat];
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
     * Group detail pemberian obat data
     *
     * Group the periksa lab data by tgl_perawatan and jam
     *
     * @param \Illuminate\Support\Collection $data
     * @return \Illuminate\Support\Collection
     */
    private function groupDetailPemberianObat(Collection $data): Collection
    {
        return $data->groupBy('no_rawat')->map(function ($grouped) {
            return $grouped->groupBy(function ($item) {
                return $item->tgl_perawatan . ' ' . $item->jam;
            });
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
            $registrasiData = \App\Models\RegPeriksa::where('no_rkm_medis', $no_rkm_medis)
                ->where('no_rawat', '<=', $no_rawat)
                ->orderBy('no_rawat', 'desc')->limit(2)
                ->pluck('no_rawat');

            return $registrasiData->toArray();
        }

        // Return the no_rawat if not in the filter
        return [$no_rawat];
    }

    public function billingData(string $no_rawat)
    {
        $tarif     = [];
        $cekGabung = \App\Models\RanapGabung::where('no_rawat', $no_rawat)->first();

        $tarif[$no_rawat] = $this->getTarif($no_rawat);

        if ($cekGabung) {
            $tarif[$cekGabung->no_rawat2] = $this->getTarif($cekGabung->no_rawat2);
        }

        return $tarif;
    }

    public function getTarif(string $no_rawat)
    {
        // +==========+==========+==========+

        $selectRawatData = ['no_rawat', DB::raw("TRIM(kd_jenis_prw) AS kd_jenis_prw"), 'biaya_rawat'];
        $rawat_inap_pr = \App\Models\RawatInapPr::select($selectRawatData)->with(["jenisPerawatan" => function ($q) {
            $q->select(DB::raw("TRIM(kd_jenis_prw) AS kd_jenis_prw"), 'nm_perawatan', 'kd_kategori')->with(['kategori' => function ($qq) {
                $qq->select('kd_kategori', 'nm_kategori');
            }]);
        }])->where('no_rawat', $no_rawat)->get()->groupBy(function ($item) {
            return $item->jenisPerawatan->kategori->nm_kategori;
        })->map(function ($group) {
            return $group->groupBy(function ($item) {
                return $item->jenisPerawatan->nm_perawatan;
            });
        });

        $rawat_inap_dr = \App\Models\RawatInapDr::select($selectRawatData)->with(["jenisPerawatan" => function ($q) {
            $q->select(DB::raw("TRIM(kd_jenis_prw) AS kd_jenis_prw"), 'nm_perawatan', 'kd_kategori')->with(['kategori' => function ($qq) {
                $qq->select('kd_kategori', 'nm_kategori');
            }]);
        }])->where('no_rawat', $no_rawat)->get()->groupBy(function ($item) {
            return $item->jenisPerawatan->kategori->nm_kategori;
        })->map(function ($group) {
            return $group->groupBy(function ($item) {
                return $item->jenisPerawatan->nm_perawatan;
            });
        });

        $rawat_inap_drpr = \App\Models\RawatInapDrPr::select($selectRawatData)->with(["jenisPerawatan" => function ($q) {
            $q->select(DB::raw("TRIM(kd_jenis_prw) AS kd_jenis_prw"), 'nm_perawatan', 'kd_kategori')->with(['kategori' => function ($qq) {
                $qq->select('kd_kategori', 'nm_kategori');
            }]);
        }])->where('no_rawat', $no_rawat)->get()->groupBy(function ($item) {
            return $item->jenisPerawatan->kategori->nm_kategori;
        })->map(function ($group) {
            return $group->groupBy(function ($item) {
                return $item->jenisPerawatan->nm_perawatan;
            });
        });

        $rawat_jl_pr = \App\Models\RawatJalanPr::select($selectRawatData)->with(["jenisPerawatan" => function ($q) {
            $q->select(DB::raw("TRIM(kd_jenis_prw) AS kd_jenis_prw"), 'nm_perawatan', 'kd_kategori')->with(['kategori' => function ($qq) {
                $qq->select('kd_kategori', 'nm_kategori');
            }]);
        }])->where('no_rawat', $no_rawat)->get()->groupBy(function ($item) {
            return $item->jenisPerawatan->kategori->nm_kategori;
        })->map(function ($group) {
            return $group->groupBy(function ($item) {
                return $item->jenisPerawatan->nm_perawatan;
            });
        });

        $rawat_jl_dr = \App\Models\RawatJalanDr::select($selectRawatData)->with(["jenisPerawatan" => function ($q) {
            $q->select(DB::raw("TRIM(kd_jenis_prw) AS kd_jenis_prw"), 'nm_perawatan', 'kd_kategori')->with(['kategori' => function ($qq) {
                $qq->select('kd_kategori', 'nm_kategori');
            }]);
        }])->where('no_rawat', $no_rawat)->get()->groupBy(function ($item) {
            return $item->jenisPerawatan->kategori->nm_kategori;
        })->map(function ($group) {
            return $group->groupBy(function ($item) {
                return $item->jenisPerawatan->nm_perawatan;
            });
        });

        $rawat_jl_drpr = \App\Models\RawatJalanDrPr::select($selectRawatData)->with(["jenisPerawatan" => function ($q) {
            $q->select(DB::raw("TRIM(kd_jenis_prw) AS kd_jenis_prw"), 'nm_perawatan', 'kd_kategori')->with(['kategori' => function ($qq) {
                $qq->select('kd_kategori', 'nm_kategori');
            }]);
        }])->where('no_rawat', $no_rawat)->get()->groupBy(function ($item) {
            return $item->jenisPerawatan->kategori->nm_kategori;
        })->map(function ($group) {
            return $group->groupBy(function ($item) {
                return $item->jenisPerawatan->nm_perawatan;
            });
        });

        // +==========+==========+==========+

        $statusLanjut = \App\Models\RegPeriksa::select('status_lanjut')->where('no_rawat', $no_rawat)->first();

        // +==========+==========+==========+

        $operasi = null;
        if (Str::lower($statusLanjut->status_lanjut) == 'ranap') {
            $operasi = (new \App\Http\Resources\Pasien\Tarif\TarifOperasi($no_rawat))->toArray(new Request(), false);
        }

        $periksaLab       = \App\Models\PeriksaLab::with('jenisPerawatan')->where('no_rawat', $no_rawat)->get()->groupBy("kd_jenis_prw");
        $periksaRadiologi = \App\Models\PeriksaRadiologi::with('jenisPerawatan')->where('no_rawat', $no_rawat)->get()->groupBy("kd_jenis_prw");

        $obatDanBhp = \App\Models\DetailPemberianObat::with('obat')->where('jml', '<>', 0)->where('no_rawat', $no_rawat)
            ->get()->groupBy(function ($q) {
            return $q->obat->nama_brng;
        })->sortKeys();

        // +==========+==========+==========+

        $mergedData = collect([
            "Pemeriksaan Lab"       => $periksaLab,
            "Pemeriksaan Radiologi" => $periksaRadiologi,
            "Obat dan BHP"          => $obatDanBhp,
            "Operasi"               => $operasi,
        ]);

        // loop the rawat inap dr
        foreach ([$rawat_inap_pr, $rawat_inap_dr, $rawat_inap_drpr, $rawat_jl_pr, $rawat_jl_dr, $rawat_jl_drpr] as $key => $value) {
            if (!$value) {
                continue;
            }

            foreach ($value as $k => $v) {
                if ($mergedData->has($k)) {
                    $mergedData[$k] = $mergedData[$k]->mergeRecursive($v);
                } else {
                    $mergedData[$k] = $v;
                }
            }
        }

        $mergedData = $mergedData->filter(function ($item) {
            return $item && !$item->isEmpty();
        })->sortKeys();

        return $mergedData;
    }
}
