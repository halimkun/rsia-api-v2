<?php

namespace App\Jobs;

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
     * Get the tags that should be assigned to the job.
     *
     * @return array<int, string>
     */
    public function tags(): array
    {
        return ['fcm-notification'];
    }

    /**
     * Determine the time at which the job should timeout.
     *
     * @return \DateTime
     */
    public function retryUntil()
    {
        return now()->addMinutes(1);
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

            // TODO : Fungsikan FirebaseCloudMessaging untuk mengirim notifikasi menggunakan template
            try {
                \App\Helpers\Notification\FirebaseCloudMessaging::withTemplate(
                    $this->template,
                    collect([
                        'pegawai' => [
                            'nama' => 'M Faisal Halim',
                            'departemen' => [
                                'dep_name' => 'IT',
                            ]
                        ],
                    ]),
                    $topic,
                    []
                );



                \App\Helpers\Logger\RSIALogger::fcm("Send notification to topic $topic with template $this->template", 'info', ['topic' => $topic, 'template' => $this->template]);
            } catch (\Exception $e) {
                \App\Helpers\Logger\RSIALogger::fcm($e->getMessage(), 'error', ['topic' => $topic, 'time' => now()->toDateTimeString()]);
            }
        }
    }
}
