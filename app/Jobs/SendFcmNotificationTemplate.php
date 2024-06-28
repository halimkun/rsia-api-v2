<?php

namespace App\Jobs;

use App\Helpers\Logger\RSIALogger;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class SendFcmNotificationTemplate implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    // Topics to send the notification
    protected $topics;

    // Template of the notification
    protected $template;

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 5;

    /**
     * The number of seconds to wait before retrying the job.
     *
     * @var int
     */
    public $backoff = 3;

    /**
     * Get the tags that should be assigned to the job.
     *
     * @return array<int, string>
     */
    public function tags(): array
    {
        return ['fcm-notification'];
    }

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(array $topics, $template)
    {
        $this->topics = $topics;
        $this->template = $template;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        foreach ($this->topics as $i => $topic) {
            
            if ($i > 0) {
                sleep(3);
            }

            try {
                // TODO : Fungsikan FirebaseCloudMessaging untuk mengirim notifikasi menggunakan template
                Log::info('Notifikasi berhasil dikirim', ['topic' => $topic, 'time' => now()->toDateTimeString()]);
            } catch (\Exception $e) {
                RSIALogger::fcm($e->getMessage(), 'error', ['topic' => $topic, 'time' => now()->toDateTimeString()]);
            }
        }
    }
}
