<?php

namespace App\Http\Controllers\v2;

use ArrayIterator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BerkasKlaimDownload extends Controller
{
    public function get($bulan, $jenis, Request $request)
    {
        $req = new Request();
        $req->merge([
            'bulan' => $bulan,
            'jenis' => $jenis,
        ]);

        return $this->download($req);
    }

    public function download(Request $request)
    {
        // validate bulan
        $request->validate([
            'bulan' => 'required|date|date_format:Y-m',
            'jenis' => 'required|in:1,2',               // 1: rawat inap, 2: rawat jalan
        ]);

        // Extract year and month from the input
        [$year, $month] = explode('-', $request->bulan);

        // root file path
        $rootPath = '/var/www/html/simrsiav2/file/berkas_klaim_pengajuan/';

        // sep data
        $data = \App\Models\BridgingSep::with('berkasPerawatan')->select('no_sep', 'no_rawat', 'jnspelayanan')
            ->whereYear('tglsep', $year)
            ->whereMonth('tglsep', $month)
            ->where('jnspelayanan', $request->jenis)
            ->get();

        // files
        $filesPerawatan = \App\Models\BerkasDigitalPerawatan::whereIn('no_rawat', $data->pluck('no_rawat')->toArray())->where('kode', '009')->get();

        // combine files with root path
        $filesPerawatan->transform(function ($item, $key) use ($rootPath) {
            $item->lokasi_file = $rootPath . $item->lokasi_file;
            return $item;
        });

        // final file locations
        $files = $filesPerawatan->pluck('lokasi_file')->toArray();

        // Cek apakah folder tempat file ada
        if (!\Illuminate\Support\Facades\File::exists($rootPath)) {
            return response()->json(['message' => 'Folder file tidak ditemukan'], 400);
        }

        // Periksa apakah ada file yang akan diarsipkan
        if (count($files) === 0) {
            return response()->json(['message' => 'Tidak ada file untuk diarsipkan'], 400);
        }

        // Filter file yang ada, untuk memastikan hanya file yang ada yang dimasukkan
        $validFiles = array_filter($files, 'file_exists');

        // pisahkan file dari root path
        $validFiles = array_map(function ($file) use ($rootPath) {
            return str_replace($rootPath, '', $file);
        }, $validFiles);

        $chunks = array_chunk($validFiles, 100);

        try {
            $tempStorage = storage_path('app/');

            // make tar.gz file per 100 files from chunks in directory /var/www/html/simrsiav2/file/berkas_klaim_pengajuan/ using tar -czf with --no-recursion
            foreach ($chunks as $key => $chunk) {
                $tarFile = $tempStorage . date('Y-m') . ($request->jenis == 1 ? '_rawat-inap' : '_rawat-jalan') . '_batch' . ($key + 1) . '.tar.gz';
                $tarCommand = 'tar -czf ' . $tarFile . ' -C ' . $rootPath . ' ' . implode(' ', $chunk) . ' --no-recursion';
                exec($tarCommand, $output, $return);

                if ($return !== 0) {
                    throw new \Exception('Gagal membuat file arsip');
                }
            }

            // combine all tar.gz files into one tar.gz file
            $tarFiles = array_map(function ($key) use ($tempStorage, $request) {
                return date('Y-m') . ($request->jenis == 1 ? '_rawat-inap' : '_rawat-jalan') . '_batch' . ($key + 1) . '.tar.gz';
            }, array_keys($chunks));

            $tarAll = $tempStorage . date('Y-m') . ($request->jenis == 1 ? '_rawat-inap' : '_rawat-jalan') . '_all.tar.gz';
            $tarCommand = 'tar -czf ' . $tarAll . ' -C ' . $tempStorage . ' ' . implode(' ', $tarFiles) . ' --no-recursion';

            exec($tarCommand, $output, $return);

            if ($return !== 0) {
                throw new \Exception('Gagal membuat file arsip');
            }

            // remove all temporary tar.gz files
            foreach ($tarFiles as $tarFile) {
                unlink($tempStorage . $tarFile);
            }

            return response()->download($tarAll, date('Y-m') . ($request->jenis == 1 ? '_rawat-inap' : '_rawat-jalan') . '_all.tar.gz')->deleteFileAfterSend(true);
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Gagal membuat file arsip ' . $th->getMessage()], 500);
        }
    }
}
