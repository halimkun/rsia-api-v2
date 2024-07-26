<?php

namespace App\Helpers\Notification;

// NOTE : body yang diterima harus berupa real string, bukan json_encode, jadi notifikasi tidak akan bermasalah dengan karakter khusus.
class ParseNotificationMessage
{
    public static function run(string $body, \Illuminate\Support\Collection $data)
    {
        // Find all placeholders in content
        preg_match_all('/\{\{\s*(.*?)\s*\}\}/', $body, $matches);
        
        // Initialize $content with $body
        $content = $body;
    
        foreach ($matches[1] as $key) {
            // Extract the value from the collection using dot notation
            $value = \Illuminate\Support\Arr::get($data, $key);
            
            if (is_null($value)) {
                \App\Helpers\Logger\RSIALogger::notifications("FCM - KEY DOES'T EXIST", 'error', ['key' => $key, 'data' => $data]);
                throw new \Exception('Key ' . $key . ' not found in data');
            }
    
            // Encode arrays as JSON and escape other values for XSS protection
            $value = is_array($value) ? json_encode($value) : htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
    
            // Replace placeholder in content with the value found
            $content = str_replace(['{{ ' . $key . ' }}', '{{' . $key . '}}'], $value, $content);
        }
    
        return $content;
    }    
}
