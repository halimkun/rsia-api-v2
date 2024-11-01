<?php

namespace App\Jobs;

use App\Http\Controllers\v2\BerkasKlaimController;
use App\Models\BridgingSep;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ExportPdfJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Key of the data to be exported
     * 
     * @var array
     * */
    protected $sep;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(string $sep)
    {
        $this->sep = $sep;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $fileName    = 'berkas-klaim-' . $this->sep . '.pdf';
        $brigdingSep = BridgingSep::where('no_sep', $this->sep)->first();

        if (!$brigdingSep) {
            \Log::channel(config('eklaim.log_channel'))->info("Final klaim failed - Export Action", [
                "sep"      => $this->sep,
                "response" => "Data SEP tidak ditemukan"
            ]);
        }

        try {

            \Halim\EKlaim\Builders\BodyBuilder::setMetadata('claim_final');
            \Halim\EKlaim\Builders\BodyBuilder::setData([
                "nomor_sep" => $this->sep,
                "coder_nik" => '3326105603750002',
            ]);

            \Halim\EKlaim\Services\EklaimService::send(\Halim\EKlaim\Builders\BodyBuilder::prepared())->then(function ($response) use ($brigdingSep) {
                \Log::channel(config('eklaim.log_channel'))->info("Final klaim success - Export Action", [
                    "sep"      => $brigdingSep->no_sep,
                    "response" => $response
                ]);
            });

            $request = new \Illuminate\Http\Request();
            $request->query->add([
                'action' => 'export',
            ]);

            $berkasKlaim = new BerkasKlaimController();
            $output = $berkasKlaim->print($brigdingSep->no_sep, $request);

            \App\Models\BerkasDigitalPerawatan::updateOrCreate(
                ['no_rawat' => $brigdingSep->no_rawat, 'kode' => '009'],
                ['lokasi_file' => $fileName]
            );

            $st = new \Illuminate\Support\Facades\Storage();
            $st::disk('sftp')->put('/simrsiav2/file/berkas_klaim_pengajuan/' . $fileName, $output);

            \Log::channel(config('eklaim.log_channel'))->info("Berkas Klaim berhasil di export - Export Action");
        } catch (\Throwable $th) {
            \Log::channel(config('eklaim.log_channel'))->error("Berkas Klaim gagal di export - Export Action", [
                "sep"     => $brigdingSep,
                "message" => $th->getMessage(),
                "trace"   => $th->getTrace()
            ]);
        }
    }
}
