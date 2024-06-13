<?php

namespace App\Http\Controllers\v2;

use App\Helpers\ApiResponse;
use App\Helpers\Notification\FirebaseCloudMessaging;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /**
     * send notification to firebase
     *
     * @param Request $request
     * @return void
     */
    public function send(Request $request)
    {
        $request->validate([
            'topic' => 'required|string',
            'title' => 'required|string',
            'body'  => 'required|string',
            'data'  => 'array'
        ]);

        $msg = (new FirebaseCloudMessaging)->buildNotification(
            $request->topic,
            $request->title,
            $request->body,
            $request->data
        );

        // FirebaseCloudMessaging::send($msg); 
        try {
            FirebaseCloudMessaging::send($msg);
            return ApiResponse::success('Notification sent successfully');
        } catch (\Exception $e) {
            return ApiResponse::error('Failed to send notification', $e->getMessage(), 500);
        }
    }

    public function withTemplate(Request $request)
    {
        $request->validate([
            // 'topic'            => 'required|string',
            'template'         => 'required|string|exists:notification_template,name',
            'data_on_template' => 'array',
            'data'             => 'array'
        ]);

        // get template from database
        $template = \App\Models\NotificationTemplate::where('name', $request->template)->first();

        // if template not found
        if (!$template) {
            return ApiResponse::error('Template not found', 'Template not found', 404);
        }

        // if template topic is empty or null validate topic from user
        if ($template->topic == null || $template->topic == '') {
            $request->validate([
                'topic' => 'required|string'
            ]);
        }

        $content = self::parsePlaceholders($template->content, $request->data_on_template);

        $msg = (new FirebaseCloudMessaging)->buildNotification(
            $template->topic ?? $request->topic,
            $template->title,
            $content,
            $request->data
        );

        try {
            FirebaseCloudMessaging::send($msg);
            return ApiResponse::success('Notification sent successfully');
        } catch (\Exception $e) {
            return ApiResponse::error('Failed to send notification', $e->getMessage(), 500);
        }
    }

    protected static function parsePlaceholders($content, $data)
    {
        // find all placeholders in content
        preg_match_all('/\{\{\s*(.*?)\s*\}\}/', $content, $matches);

        foreach ($matches[1] as $key) {
            // Mengurai kunci untuk mengambil bagian-bagian yang diperlukan
            $keys = explode('.', $key);
            $value = $data;

            $keyExists = true;

            foreach ($keys as $k) {
                // Mengakses nilai yang sesuai dengan kunci yang diberikan
                if (isset($value[$k])) {
                    $value = $value[$k];
                } else {
                    $value = null;
                    $keyExists = false;
                    break;
                }
            }

            if (!$keyExists) {
                throw new \Exception('Key ' . $key . ' not found in data');
            }

            // Menyaring nilai untuk menghindari XSS atau data yang tidak valid
            $value = is_array($value) ? json_encode($value) : htmlspecialchars($value);

            // Mengganti placeholder di konten dengan nilai yang ditemukan
            $content = str_replace('{{ ' . $key . ' }}', $value, $content);
            $content = str_replace('{{' . $key . '}}', $value, $content);
        }

        return $content;
    }
}
