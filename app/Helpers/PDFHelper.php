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
    public static function generate(string $view, array $data)
    {
        $f4 = [0, 0, 609.448, 935.432];
        $orientation = 'portrait';

        // Cache key using view name and hash of data
        $cacheKey = 'pdf_' . md5($view . serialize($data));

        // Check if PDF already exists in cache
        return Cache::remember($cacheKey, 3600, function () use ($view, $data, $f4, $orientation) {
            return Pdf::loadView($view, $data)->setPaper($f4, $orientation)->output();
        });
    }

    /**
     * Merge multiple PDFs
     * 
     * @param array $pdfs
     * @return \Webklex\PDFMerger\PDFMerger
     */
    public static function merge(array $pdfs)
    {
        // merge the PDFs to a single PDF
        $merger = PDFMerger::init();
        foreach ($pdfs as $pdf) {
            if ($pdf instanceof \Barryvdh\DomPDF\PDF) {
                $merger->addString($pdf->output(), 'all');
            } else if (is_string($pdf)) {
                $merger->addString($pdf, 'all');
            } else {
                continue;
            }

        }

        // finalize the PDF
        $merger->merge();

        return $merger;
    }
}
