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
        $bSep     = \App\Models\BridgingSep::with(['pasien', 'reg_periksa', 'dokter.pegawai.sidikjari' => function($q) {
            $q->select('id', \DB::raw('SHA1(sidikjari) as sidikjari'));
        }])->where('no_sep', $sep)->first();
        $diagnosa = \App\Models\DiagnosaPasien::with('penyakit')->where('no_rawat', $bSep->no_rawat)->orderBy('prioritas', 'asc')->get();
        $prosedur = \App\Models\ProsedurPasien::with('penyakit')->where('no_rawat', $bSep->no_rawat)->orderBy('prioritas', 'asc')->get();

        // ========== BERKAS
        $berkasSep = Pdf::loadView('berkas-klaim.partials.sep', [
            'sep'      => $bSep,
            'diagnosa' => $diagnosa,
            'prosedur' => $prosedur,
        ])->setPaper($this->f4, $this->orientation);

        // return view('berkas-klaim.partials.sep', [
        //     'sep'      => $bSep,
        //     'diagnosa' => $diagnosa,
        //     'prosedur' => $prosedur,
        // ]);

        // ========== MERGER PREPARE
        $this->oMerger->addString($berkasSep->output(), 'all');

        // ========== MERGE FINAL
        $this->oMerger->merge();
        $this->oMerger->setFileName('berkas-klaim-' . $sep . '.pdf');

        // ========== RESPONSE
        return response($this->oMerger->stream())
            ->header('Content-Type', 'application/pdf')
            ->header('Pragma', 'no-cache')
            ->header('Expires', '0');
    }


    // public function test($sep, Request $request)
    // {
    //     $pdfMerger = PDFMerger::init();
    //     $htmls = [
    //         '<h1>Test 1</h1>',
    //         '<h1>Test 2</h1>',
    //         '<h1>Test 3</h1>',
    //         '<h1>Test 4</h1>',
    //         '<h1>Test 5</h1>',
    //     ];

    //     foreach ($htmls as $html) {
    //         $pdf = App::make('dompdf.wrapper');
    //         $pdf->loadHTML($html);
    //         $pdfMerger->addString($pdf->output(), 'all');
    //     }

    //     $pdfMerger->merge();

    //     // $pdf1 = App::make('dompdf.wrapper');
    //     // $pdf1->loadHTML('<h1>Test 1</h1>');

    //     // $pdf2 = App::make('dompdf.wrapper');
    //     // $pdf2->loadHTML('<h1>Test 2</h1>');

    //     // $pdfMerger->addString($pdf1->output(), 'all');
    //     // $pdfMerger->addString($pdf2->output(), 'all');

    //     // $pdfMerger->merge();

    //     return response($pdfMerger->stream())->header('Content-Type', 'application/pdf')->header('Content-Disposition', 'inline; filename="test.pdf"')->header('Cache-Control', 'no-cache, no-store, must-revalidate')->header('Pragma', 'no-cache')->header('Expires', '0');
    // }
}
