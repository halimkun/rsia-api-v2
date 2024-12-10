<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class RsiaNotifikasiUndangan extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rsia:notif-undangan';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Melakukan pengecekan undangan, dan mengirimkan notifikasi undangan ke pegawai yang bersangkutan';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $now = \Carbon\Carbon::now()->format('Y-m-d');
        $suratIntetnal = \App\Models\RsiaSuratInternal::with([
            'penerimaUndangan' => function ($query) {
                $query->with(['pegawai', 'petugas']);
            }
        ])->whereHas('penerima')->whereDate('tanggal', $now)->get();

        // if empty return
        if ($suratIntetnal->isEmpty()) {
            return;
        }

        foreach ($suratIntetnal as $item) {
            $petugas = $item->penerimaUndangan->pluck('petugas')->toArray();
            
            $d = random_int(30, 90);
            foreach ($petugas as $p) {
                if (!$this->checkValidityPhoneNumber($p['no_telp'])) {
                    continue;
                }

                $msg = "👋 Selamat Pagi, *" . $p['nama'] . "*\n";
                $msg .= "Anda memiliki undangan pada ini : " . "\n\n";

                // perihal
                $msg .= "*Perihal* : " . $item['perihal'] . "\n";
                $msg .= "*Tanggal* : " . $item['tanggal'] . "\n";
                $msg .= "*Tempat* : " . $item['tempat'] . "\n\n";

                $msg .= "Mohon untuk hadir tepat waktu. Terima kasih 🙏.";

                \App\Jobs\SendWhatsApp::dispatch(
                    $this->buildNumberWithCountryCode($p['no_telp']),
                    $msg
                )
                 ->onQueue('otp')
                 ->delay(now()->addSeconds($d));

                $d += random_int(25, 75);
            }
        }
    }

    private function checkValidityPhoneNumber($number)
    {
        if (strlen($number) < 10 || strlen($number) > 13) {
            \Log::error("Invalid phone number: " . $number);
            return false;
        }

        return true;
    }

    private function buildNumberWithCountryCode($number)
    {
        return \Illuminate\Support\Str::startsWith($number, '0') ? '62' . substr($number, 1) : $number;
    }
}
