<?php 
namespace App\Helpers\Logger;

class BerkasLogger {
    public static function make($message, $level = 'info', $context = []) {
        // append user id to context
        $user = \Illuminate\Support\Facades\Auth::guard('user-aes')->user();
        $context['user'] = $user->id_user;

        // log the message
        $logger = \Illuminate\Support\Facades\Log::channel('berkas');
        $logger->$level($message, $context);
    }
}