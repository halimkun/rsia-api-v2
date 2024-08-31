<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Http;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class SendWhatsApp implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The phone number to send the OTP
     * 
     * @var string
     * */
    protected $noHp;

    /**
     * The OTP code
     * 
     * @var string
     * */
    protected $message;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(String $noHp, String $message)
    {
        $this->noHp = $noHp;
        $this->message = $message;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        /**
         * Simulate a delay of 10-60 seconds
         * to simulate the process of sending OTP via WhatsApp
         */
        // $random = random_int(10, 60);
        // sleep($random);

        // Send OTP via WhatsApp
        $apiWhatsappUrl = env('API_WHATSAPP_URL');

        $realNoHp = $this->noHp . "@s.whatsapp.net";

        $response = Http::withHeaders([
            'Authorization' => 'Basic ' . base64_encode(env('API_WHATSAPP_USERNAME') . ':' . env('API_WHATSAPP_PASSWORD'))
        ])->post("$apiWhatsappUrl/send/message", [
            'phone'   => $realNoHp,
            'message' => $this->message
        ]);

        if ($response->successful()) {
            \App\Helpers\Logger\RSIALogger::notifications("OTP sent to $this->noHp");
        } else {
            \App\Helpers\Logger\RSIALogger::notifications("Failed to send OTP to $this->noHp", 'error');
        }
    }
}
