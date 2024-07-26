<?php

namespace App\Helpers\Logger;

class RSIALogger
{
    public static function berkas($message, $level = 'info', $context = [])
    {
        // append user id to context
        $user = \Illuminate\Support\Facades\Auth::guard('user-aes')->user();
        $context['user'] = $user->id_user ?? 'unknown';

        // log the message
        $logger = \Illuminate\Support\Facades\Log::channel('berkas');
        $logger->$level($message, $context);
    }

    public static function undangan($message, $level = 'info', $context = [])
    {
        // append user id to context
        $user = \Illuminate\Support\Facades\Auth::guard('user-aes')->user();
        $context['user'] = $user->id_user ?? 'unknown';

        // log the message
        $logger = \Illuminate\Support\Facades\Log::channel('undangan');
        $logger->$level($message, $context);
    }

    public static function kehadiran($message, $level = 'info', $context = [])
    {
        // append user id to context
        $user = \Illuminate\Support\Facades\Auth::guard('user-aes')->user();
        $context['user'] = $user->id_user ?? 'unknown';

        // log the message
        $logger = \Illuminate\Support\Facades\Log::channel('kehadiran');
        $logger->$level($message, $context);
    }

    public static function notifications($message, $level = 'info', $context = [])
    {
        // append user id to context
        $user = \Illuminate\Support\Facades\Auth::guard('user-aes')->user();
        $context['user'] = $user->id_user ?? 'unknown';

        // log the message
        $logger = \Illuminate\Support\Facades\Log::channel('notifications');
        $logger->$level($message, $context);
    }
}
