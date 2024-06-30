<?php

namespace App\Helpers\Notification;

use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use Request;

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
                \App\Helpers\Logger\RSIALogger::fcm('Key not found in data', 'error', ['key' => $key, 'data' => $data]);
                throw new \Exception('Key ' . $key . ' not found in data');
            }
    
            // Encode arrays as JSON and escape other values for XSS protection
            $value = is_array($value) ? json_encode($value) : htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
    
            // Replace placeholder in content with the value found
            $content = str_replace(['{{ ' . $key . ' }}', '{{' . $key . '}}'], $value, $content);
            \App\Helpers\Logger\RSIALogger::fcm('Parse notification message', 'info', ['key' => $key, 'value' => $value, 'content' => $content]);
        }
    
        return $content;
    }    
}
