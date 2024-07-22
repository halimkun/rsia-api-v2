<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class JadwalPraktikDokter implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Topics to send the notification
     * 
     * @var string
     * */
    protected $topics;

    /**
     * Template of the notification
     * 
     * @var string
     * */
    protected $templateName;

    /**
     * Request instance
     * 
     * @var \Illuminate\Http\Request|null
     * */ 
    protected $request;

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
    public function __construct($templateName, string $topics, \Illuminate\Support\Collection $request = null)
    {
        $this->topics       = $topics;
        $this->templateName = $templateName;
        $this->request      = $request;
    }

    /**
     * Get the tags that should be assigned to the job.
     *
     * @return array<int, string>
     */
    public function tags(): array
    {
        return ['fcm-notification', 'pasien', 'notification'];
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // get template from database
        $template = \App\Models\RsiaTemplateNotifikasi::where('name', $this->templateName)->first();

        // if template not found
        if (!$template) {
            \App\Helpers\Logger\RSIALogger::fcm('TEMPLATE NOT FOUND', 'error', ['template' => $this->templateName]);
            throw new \Exception('Template notifikasi tidak ditemukan');
        }

        $pasien        = \App\Models\Pasien::where('no_rkm_medis', $this->topics)->first();
        $dokter        = $this->request->get('nik') ? \App\Models\Dokter::where('kd_dokter', $this->request->get('nik'))->first() : collect([]);
        $parsedRequest = $this->request->get('parse') ? collect($this->request->get('parse')) : collect([]);

        $templateData = $parsedRequest->merge([
            'pasien' => $pasien,
            'dokter' => $dokter
        ]);

        \App\Helpers\Logger\RSIALogger::fcm("NOTIFICATION SENT", 'info', ['topic' => $this->topics, 'template' => $this->templateName]);
        $pasien->notify(new \App\Notifications\Pasien\JadwalPraktikDokter($this->topics, $template, $templateData));
    }
}
