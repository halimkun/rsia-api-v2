<?php

namespace App\Notifications\Channel;

use Throwable;
use ReflectionException;
use Kreait\Firebase\Messaging\Message;
use NotificationChannels\Fcm\FcmMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Events\Dispatcher;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Exception\MessagingException;
use Illuminate\Notifications\Events\NotificationFailed;
use Illuminate\Contracts\Container\BindingResolutionException;
use NotificationChannels\Fcm\Exceptions\CouldNotSendNotification;

class FcmTopicChannel
{
    const MAX_TOKEN_PER_REQUEST = 500;

    /**
     * @var \Illuminate\Contracts\Events\Dispatcher
     */
    protected $events;

    /**
     * FcmChannel constructor.
     *
     * @param  \Illuminate\Contracts\Events\Dispatcher  $dispatcher
     */
    public function __construct()
    {
        $this->events = app('events');
    }

    /**
     * @var string|null
     */
    protected $fcmProject = null;

    /**
     * Send the given notification.
     *
     * @param  mixed  $notifiable
     * @param  \Illuminate\Notifications\Notification  $notification
     * @return array
     *
     * @throws \NotificationChannels\Fcm\Exceptions\CouldNotSendNotification
     * @throws \Kreait\Firebase\Exception\FirebaseException
     */
    public function send($notifiable, Notification $notification)
    {
        // Get the message from the notification class
        $fcmMessage = $notification->toFcm($notifiable);
        
        // Get the topic from the fcmMessage
        $topic = $fcmMessage->getTopic();

        if (empty($topic)) {
            throw new \Exception('Topic is required');
        }
        
        if (!$fcmMessage instanceof Message) {
            throw CouldNotSendNotification::invalidMessage();
        }

        if (method_exists($notification, 'fcmProject')) {
            $this->fcmProject = $notification->fcmProject($notifiable, $fcmMessage);
        }

        $responses = [];

        try {
            $responses[] = $this->sendToFcm($fcmMessage, $topic);
        } catch (MessagingException $exception) {
            $this->failedNotification($notifiable, $notification, $exception, $topic);
            throw CouldNotSendNotification::serviceRespondedWithAnError($exception);
        }

        return $responses;
    }


    /**
     * @return \Kreait\Firebase\Messaging
     */
    protected function messaging()
    {
        try {
            $messaging = app('firebase.manager')->project($this->fcmProject)->messaging();
        } catch (BindingResolutionException $e) {
            $messaging = app('firebase.messaging');
        } catch (ReflectionException $e) {
            $messaging = app('firebase.messaging');
        }

        return $messaging;
    }

    /**
     * @param  \Kreait\Firebase\Messaging\Message  $fcmMessage
     * @param $token
     * @return array
     *
     * @throws \Kreait\Firebase\Exception\MessagingException
     * @throws \Kreait\Firebase\Exception\FirebaseException
     */
    protected function sendToFcm(Message $fcmMessage, $token)
    {
        if ($fcmMessage instanceof CloudMessage) {
            $fcmMessage = $fcmMessage->withChangedTarget('topic', $token);
        }

        if ($fcmMessage instanceof FcmMessage) {
            $fcmMessage->setTopic($token);
        }

        return $this->messaging()->send($fcmMessage);
    }

    /**
     * @param $fcmMessage
     * @param  array  $tokens
     * @return \Kreait\Firebase\Messaging\MulticastSendReport
     *
     * @throws \Kreait\Firebase\Exception\MessagingException
     * @throws \Kreait\Firebase\Exception\FirebaseException
     */
    protected function sendToFcmMulticast($fcmMessage, array $tokens)
    {
        return $this->messaging()->sendMulticast($fcmMessage, $tokens);
    }

    /**
     * Dispatch failed event.
     *
     * @param  mixed  $notifiable
     * @param  \Illuminate\Notifications\Notification  $notification
     * @param  \Throwable  $exception
     * @param  string|array  $token
     * @return array|null
     */
    protected function failedNotification($notifiable, Notification $notification, Throwable $exception, $token)
    {
        return $this->events->dispatch(new NotificationFailed(
            $notifiable,
            $notification,
            self::class,
            [
                'message' => $exception->getMessage(),
                'exception' => $exception,
                'token' => $token,
            ]
        ));
    }
}
