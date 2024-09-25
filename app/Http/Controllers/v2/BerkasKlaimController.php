<?php

namespace App\Http\Controllers\v2;

use App\Helpers\PDFHelper;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
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

    /**
     * Cetak berkas klaim
     * 
     * @param string $sep
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function print($sep, Request $request)
    {
        \Illuminate\Support\Facades\Artisan::call('cache:clear');

        $bSep = \Illuminate\Support\Facades\Cache::remember("bsep_{$sep}", 3600, function () use ($sep) {
            return \App\Models\BridgingSep::with(['pasien', 'reg_periksa', 'dokter.pegawai.sidikjari', 'surat_kontrol'])->where('no_sep', $sep)->first();
        });

        $regPeriksa        = \Illuminate\Support\Facades\Cache::remember("regPeriksa_{$sep}", 3600, function () use ($bSep) {
            return \App\Models\RegPeriksa::with(['pasien', 'diagnosaPasien.penyakit', 'prosedurPasien.penyakit', 'catatanPerawatan'])->where('no_rawat', $bSep->no_rawat)->first();
        });

        $triase            = \Illuminate\Support\Facades\Cache::remember("triase_{$sep}", 3600, function () use ($bSep) {
            return \App\Models\RsiaTriaseUgd::where('no_rawat', $bSep->no_rawat)->first();
        });

        $kamarInap         = \App\Models\KamarInap::with('kamar.bangsal')
            ->where('no_rawat', $bSep->no_rawat)->where('stts_pulang', '<>', 'Pindah Kamar')
            ->orderBy('tgl_masuk', 'desc')->orderBy('jam_masuk', 'desc')->get();

        $operasi           = \App\Models\RsiaOperasiSafe::withAllRelations()->where('no_rawat', $bSep->no_rawat)->get();
        $resumePasienRanap = \App\Models\ResumePasienRanap::where('no_rawat', $bSep->no_rawat)->first();
        $spri              = \App\Models\BridgingSuratPriBpjs::where('no_surat', $bSep->noskdp)->first();
        $radiologi         = \App\Models\PeriksaRadiologi::select('no_rawat', 'nip', 'kd_jenis_prw', 'tgl_periksa', 'jam', 'dokter_perujuk', 'kd_dokter', 'status')->with(['dokter.sidikjari', 'dokterPerujuk', 'hasilRadiologi', 'jenisPerawatan', 'petugas.sidikjari'])->where('no_rawat', $bSep->no_rawat)->orderBy('tgl_periksa', 'ASC')->orderBy('jam', 'ASC')->get();

        $lab               = \Illuminate\Support\Facades\Cache::remember("lab_{$sep}", 3600, function () use ($regPeriksa, $bSep) {
            return $this->groupPeriksaLabData(
                \App\Models\PeriksaLab::with('pegawai.sidikjari', 'dokter.sidikjari', 'perujuk', 'jenisPerawatan', 'detailPeriksaLab.template')
                    ->whereIn('no_rawat', $this->getRegisterLabDouble($regPeriksa->kd_poli, $bSep->no_rawat, $bSep->nomr))
                    ->orderBy('tgl_periksa', 'ASC')->orderBy('jam', 'ASC')->get()
            );
        });

        $obat              = $this->groupDetailPemberianObat(\App\Models\DetailPemberianObat::select('tgl_perawatan', 'jam', 'no_rawat', 'kode_brng', 'jml')->with('obat')->whereIn('no_rawat', $this->cekGabung($bSep->no_rawat))->get());

        $berkasPendukung   = \App\Models\RsiaUpload::where('no_rawat', $bSep->no_rawat)->get()->map(function ($item) {
            $item->kategori = strtolower($item->kategori);
            return $item;
        });

        $ttdPasien         = \App\Models\RsiaVerifSep::where('no_sep', $sep)->first();
        $ttdResume         = \App\Models\Pegawai::with(['sidikjari', 'dep'])->whereHas('dep', function ($q) use ($kamarInap) {
            return $q->where('nama', \Illuminate\Support\Str::upper($this->getDepartemen($kamarInap)));
        })->where('status_koor', '1')->first();

        // +==========+==========+==========+

        $pdfs = [
            $this->genSep($bSep, $regPeriksa->diagnosaPasien, $regPeriksa->prosedurPasien),
        ];

        if ($resumePasienRanap) {
            $pdfs[] = $this->genResumeMedis($bSep, $regPeriksa->pasien, $regPeriksa, $kamarInap, $resumePasienRanap, $ttdResume, $bSep->dokter->pegawai, $ttdPasien);
        }
        
        if ($operasi) {
            $pdfs = array_merge($pdfs, $this->genLaporanOperasi($regPeriksa, $operasi));
        }

        if ($spri) {
            $pdfs[] = $this->genSuratPerintahRawatInap($bSep, $regPeriksa->pasien, $spri);
        }

        if ($bSep->surat_kontrol) {
            $pdfs[] = $this->genSuratRencanaKontrol($bSep, $regPeriksa);
        }

        if ($this->genBerkasPendukung(['skl'], $berkasPendukung) && !empty($this->genBerkasPendukung(['skl'], $berkasPendukung))) {
            $pdfs = array_merge($pdfs, $this->genBerkasPendukung(['skl'], $berkasPendukung));
        }

        if ($regPeriksa->catatanPerawatan) {
            $pdfs[] = $this->genHasilPemeriksaanUsg($bSep, $regPeriksa->pasien, $regPeriksa);
        }

        if ($this->genBerkasPendukung(['surat rujukan'], $berkasPendukung) && !empty($this->genBerkasPendukung(['surat rujukan'], $berkasPendukung))) {
            $pdfs = array_merge($pdfs, $this->genBerkasPendukung(['surat rujukan'], $berkasPendukung));
        }

        if ($this->genBerkasPendukung(['usg'], $berkasPendukung) && !empty($this->genBerkasPendukung(['usg'], $berkasPendukung))) {
            $pdfs = array_merge($pdfs, $this->genBerkasPendukung(['usg'], $berkasPendukung));
        }

        if ($this->genHasilLab($bSep, $regPeriksa, $lab) && !empty($this->genHasilLab($bSep, $regPeriksa, $lab))) {
            $pdfs = array_merge($pdfs, $this->genHasilLab($bSep, $regPeriksa, $lab));
        }

        if ($this->genHasilPeriksaRadiologi($regPeriksa, $radiologi) && !empty($this->genHasilPeriksaRadiologi($regPeriksa, $radiologi))) {
            $pdfs = array_merge($pdfs, $this->genHasilPeriksaRadiologi($regPeriksa, $radiologi));
        }

        if ($this->genBerkasPendukung(['laborat'], $berkasPendukung) && !empty($this->genBerkasPendukung(['laborat'], $berkasPendukung))) {
            $pdfs = array_merge($pdfs, $this->genBerkasPendukung(['laborat'], $berkasPendukung));
        }

        if ($this->genBerkasPendukung(['skl', 'surat rujukan', 'usg', 'laborat'], $berkasPendukung, true) && !empty($this->genBerkasPendukung(['skl', 'surat rujukan', 'usg', 'laborat'], $berkasPendukung, true))) {
            $pdfs = array_merge($pdfs, $this->genBerkasPendukung(['skl', 'surat rujukan', 'usg', 'laborat'], $berkasPendukung, true));
        }

        if ($this->genDetailObat($obat, $regPeriksa) && !empty($this->genDetailObat($obat, $regPeriksa))) {
            $regPeriksa = $regPeriksa->whereIn('no_rawat', array_keys($obat->toArray()))->get()->keyBy('no_rawat');

            $detailObat = [];
            foreach ($obat as $key => $value) {
                foreach ($value as $sk => $sv) {
                    // table obat
                    $detailObat[$key] = \Illuminate\Support\Facades\View::make('berkas-klaim.partials.obat', [
                        'obat'       => $value,
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

                $pdfs[] = $mpdf->Output('obat.pdf', 'S');
            }
        }

        $inacbgReport = $this->genInacbgReport($sep);
        if ($inacbgReport) {
            $pdfs[] = $inacbgReport;
        }

        // php artisan view:clear
        \Illuminate\Support\Facades\Artisan::call('view:clear');

        $pdf = PDFHelper::merge($pdfs);

        // +==========+==========+==========+

        $pdf->setFileName('berkas-klaim-' . $sep . '.pdf');

        // +==========+==========+==========+

        return response($pdf->stream(), 200)
            ->header('Content-Type', 'application/pdf')
            ->header('Pragma', 'no-cache')
            ->header('Expires', '0');
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
            "nomor_sep"     => $sep,
        ]);

        $response = \Halim\EKlaim\Services\EklaimService::send(\Halim\EKlaim\Builders\BodyBuilder::prepared());

        if ($response->getStatusCode() == 200) {
            $resp = $response->getData();

            if (!$resp->data) {
                \Log::channel(config('eklaim.log_channel'))->error('Berkas Klaim Print - Failed to generate INACBG report', [
                    'sep' => $sep,
                    'response' => $resp,
                ]);

                return null;
            }

            return base64_decode($resp->data);
        }

        \Log::channel(config('eklaim.log_channel'))->error('Berkas Klaim Print - Failed to generate INACBG report', [
            'sep'      => $sep,
            'response' => $response->getData(),
        ]);

        return null;
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
    public function genSuratPerintahRawatInap($brigdingSep, $pasien, $spri)
    {
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
    public function genHasilLab($sep, $regPeriksa, $lab)
    {
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
        $detailObat = [];

        // Eager load the related models to avoid N+1 problem
        $regPeriksa = $regPeriksa->whereIn('no_rawat', array_keys($obat->toArray()))->get()->keyBy('no_rawat');

        foreach ($obat as $key => $value) {
            $detailObat[$key] = PDFHelper::generate('berkas-klaim.partials.obat', [
                'regPeriksa' => $regPeriksa->get($key),
                'obat'       => $value,
            ]);
        }

        return $detailObat;
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
    public function genBerkasPendukung(array $kategori, $berkasPendukung, bool $notInKategori = false)
    {
        if ($berkasPendukung->isEmpty()) {
            return [];
        }

        $pendukung = [];

        if ($notInKategori) {
            foreach ($berkasPendukung as $key => $value) {
                if (!$berkasPendukung->where('kategori', $value->kategori)->first()) {
                    continue;
                }

                if (!in_array($value->kategori, $kategori)) {
                    $files = explode(',', $value->file);

                    foreach ($files as $file) {
                        $pendukung[] = PDFHelper::generate('berkas-klaim.partials.image', [
                            'image' => $file,
                            'alt'   => Str::title($value->kategori),
                        ]);
                    }
                }
            }

            return $pendukung;
        }

        foreach ($kategori as $key => $value) {
            if (!$berkasPendukung->where('kategori', $value)->first()) {
                continue;
            }

            $file = $berkasPendukung->where('kategori', $value)->first()?->file;
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
                ->orderBy('no_rawat', 'desc')
                ->limit(2)
                ->pluck('no_rawat');

            return $registrasiData->toArray();
        }

        // Return the no_rawat if not in the filter
        return [$no_rawat];
    }
}
