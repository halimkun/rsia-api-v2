<?php

namespace App\Http\Controllers\v2;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use STS\ZipStream\Facades\Zip;

class BerkasKlaimDownload extends Controller
{
    public function download(Request $request) 
    {
        // validate bulan
        $request->validate([
            'bulan' => 'required|date|date_format:Y-m',
        ]);

        // Extract year and month from the input
        [$year, $month] = explode('-', $request->bulan);

        // root file path
        $rootPath = '/var/www/html/simrsiav2/file/berkas_klaim_pengajuan/';

        // sep data
        $data = \App\Models\BridgingSep::select('no_sep', 'no_rawat', 'jnspelayanan')
            ->whereYear('tglsep', $year)
            ->whereMonth('tglsep', $month)
            ->get();

        $noRawatRanap = $data->where('jnspelayanan', 1)->pluck('no_rawat')->toArray();
        $noRawatRalan = $data->where('jnspelayanan', 2)->pluck('no_rawat')->toArray();

        // files
        $filesRawatInap = \App\Models\BerkasDigitalPerawatan::whereIn('no_rawat', $noRawatRanap)->where('kode', '009')->get();
        $filesRawatJalan = \App\Models\BerkasDigitalPerawatan::whereIn('no_rawat', $noRawatRalan)->where('kode', '009')->get();

        // combine files with root path
        $filesRawatInap->transform(function ($item, $key) use ($rootPath) {
            $item->lokasi_file = $rootPath . $item->lokasi_file;
            return $item;
        });

        $filesRawatJalan->transform(function ($item, $key) use ($rootPath) {
            $item->lokasi_file = $rootPath . $item->lokasi_file;
            return $item;
        });

        $this->makeZip($filesRawatJalan->pluck('lokasi_file')->toArray());
    }

    public function makeZip(array $files)
    {
        // files is full path of files name and location
        // check if file exists, if not exists, remove from array
        $files = array_filter($files, function ($file) {
            return file_exists($file);
        });

        $zipper = new \Madnest\Madzipper\Madzipper;
        $zipper->make(storage_path('app/berkas_klaim.zip'))->add($files)->close();
    }
}
