<?php

namespace App\Helpers\Notification;

use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use Request;

class FirebaseCloudMessaging
{
    private $credentials = 'firebase-credentials.json';

    private $factory;

    protected $messaging;


    public function __construct()
    {
        // initialize firebase 
        $this->factory = (new Factory)->withServiceAccount(base_path($this->credentials));

        // get messaging instance
        $this->messaging = $this->factory->createMessaging();
    }

    public static function send($msg)
    {
        (new self)->messaging->send($msg);
    }

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
