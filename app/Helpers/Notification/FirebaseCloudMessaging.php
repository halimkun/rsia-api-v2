<?php

namespace App\Helpers\Notification;

use Request;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use App\Helpers\Notification\ParseNotificationMessage;

class FirebaseCloudMessaging
{
    private $credentials = 'firebase-credentials--rsia-aisyiyah.json';

    private $factory;

    protected $messaging;


    /**
     * Create a new instance of FirebaseCloudMessaging
     * 
     * @return void
     * */
    public function __construct()
    {
        // initialize firebase 
        $this->factory = (new Factory)->withServiceAccount(base_path($this->credentials));

        // get messaging instance
        $this->messaging = $this->factory->createMessaging();
    }

    /**
     * Send notification to firebase cloud messaging
     * 
     * @param CloudMessage $msg
     * @return void
     * */
    public static function send(CloudMessage $msg)
    {
        (new self)->messaging->send($msg);
    }

    /**
     * Send notification with template
     * 
     * @param string $template
     * @param object $templateData (optional) : data to replace on template
     * @param string $topic (optional) : topics to send the notification
     * @param array $notificationData (optional) : additional data to send with notification
     * 
     * @return void
     * */
    
    public static function withTemplate($template, \Illuminate\Support\Collection $templateData, string $topic = '', array $notificationData = [])
    {
        // get template from database
        $template = \App\Models\RsiaTemplateNotifikasi::where('name', $template)->first();

        // if template not found
        if (!$template) {
            \App\Helpers\Logger\RSIALogger::fcm('Template not found', 'error', ['template' => $template]);
            return \App\Helpers\ApiResponse::error("Notification template not found", 'resource_not_found', null, 404);
        }

        $content = ParseNotificationMessage::run($template->content, $templateData);

        // build notification message
        $msg = (new self)->buildNotification(
            $topic ?: ($template->topic ?? ''),
            $template->title,
            $content,
            $notificationData
        );

        // send notification
        self::send($msg);
    }

    /**
     * Build notification message
     * 
     * @param string $topic
     * @param string $title
     * @param string $body
     * @param array $data (optional)
     * 
     * @return CloudMessage
     * */
    public function buildNotification($topic, $title, $body, $data = [])
    {
        return CloudMessage::withTarget('topic', $topic)
            ->withNotification([
                'topic' => $topic,
                'title' => $title,
                'body' => $body
            ])
            ->withData($data ?? []);
    }
}
