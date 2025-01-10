<?php

namespace App\Helpers;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Cache;
use Webklex\PDFMerger\Facades\PDFMergerFacade as PDFMerger;

class PDFHelper
{
    /**
     * Generate PDF
     * 
     * @param string $view
     * @param array $data
     * @return \Barryvdh\DomPDF\PDF
     */
    public static function generate(string $view, array $data, bool $raw = true)
    {
        $f4 = [0, 0, 609.448, 935.432];
        $orientation = 'portrait';

        // Check if PDF already exists in cache
        $last = Pdf::loadView($view, $data)->setPaper($f4, $orientation);

        if ($raw) {
            return $last->output();
        }

        return $last;
    }

    /**
     * Merge multiple PDFs
     * 
     * @param array $pdfs
     * @return \Webklex\PDFMerger\PDFMerger
     */
    public static function merge(array $pdfs)
    {
        $else = [];

        // merge the PDFs to a single PDF
        $merger = PDFMerger::init();
        foreach ($pdfs as $key => $pdf) {
            if ($pdf instanceof \Barryvdh\DomPDF\PDF) {
                $merger->addString($pdf->output(), 'all');
            } else if (is_string($pdf)) {
                $merger->addString($pdf, 'all');
            } else {
                $else[] = $key;
                continue;
            }
        }

        // finalize the PDF
        $merger->merge();

        return $merger;
    }
}
