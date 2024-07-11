<?php

namespace App\Notifications\Pasien;

use Illuminate\Bus\Queueable;
use NotificationChannels\Fcm\FcmMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Notifications\Channel\FcmTopicChannel;
use NotificationChannels\Fcm\Resources\Notification as FcmNotification;


class JadwalPraktikDokter extends Notification
{
    use Queueable;

    /**
     * The topic of the notification.
     * 
     * @var string
     * */
    protected $topic;

    /**
     * The notification template instance.
     * 
     * @var \App\Models\RsiaTemplateNotifikasi
     * */
    protected $notificationTemplate;

    /**
     * The message data to be parsed.
     * 
     * @var \Illuminate\Support\Collection|null
     * */
    protected $messageData;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(string $topic, \App\Models\RsiaTemplateNotifikasi $notificationTemplate, \Illuminate\Support\Collection $messageData = null)
    {
        $this->topic                = $topic;
        $this->notificationTemplate = $notificationTemplate;
        $this->messageData          = $messageData;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database', FcmTopicChannel::class];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toFcm($notifiable)
    {
        $template = $this->doParseTemplate($notifiable);

        $message = FcmMessage::create();
        $message->setTopic($this->topic) // <- this is the topic, change this to your topic for testing purpose
            ->setName($this->notificationTemplate->name)
            ->setNotification(
                FcmNotification::create()
                    ->setTitle($this->notificationTemplate->title)
                    ->setBody($template['body'])
            )
            ->setData([
                'type' => $this->notificationTemplate->type,
                'message' => $template['body'],
            ])->setAndroid(
                \NotificationChannels\Fcm\Resources\AndroidConfig::create()
                    ->setPriority(\NotificationChannels\Fcm\Resources\AndroidMessagePriority::HIGH())
                    ->setNotification(\NotificationChannels\Fcm\Resources\AndroidNotification::create()->setColor('#003300')->setTag($this->notificationTemplate->type))
            );

        return $message;
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        $template = $this->doParseTemplate($notifiable);

        return [
            'title'   => $this->notificationTemplate->title,
            'body'    => $template['body'],
            'detail'  => $template['detail'],
            'type'    => $this->notificationTemplate->type,
            'message' => $template['body'],
            'tag'     => $this->notificationTemplate->type,
            'topic'   => $this->topic
        ];
    }

    /**
     * Store the notification in the database.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toDatabase($notifiable)
    {
        $template = $this->doParseTemplate($notifiable);

        return [
            'title'   => $this->notificationTemplate->title,
            'body'    => $template['body'],
            'detail'  => $template['detail'],
            'type'    => $this->notificationTemplate->type,
            'message' => $template['body'],
            'tag'     => $this->notificationTemplate->type,
            'topic'   => $this->topic
        ];
    }

    private function doParseTemplate($notifiable)
    {
        $body   = \App\Helpers\Notification\ParseNotificationMessage::run($this->notificationTemplate->body, $this->messageData);
        $detail = \App\Helpers\Notification\ParseNotificationMessage::run($this->notificationTemplate->detail, $this->messageData);

        return [
            'body'   => $body,
            'detail' => $detail
        ];
    }
}
