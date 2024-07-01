<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponse;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // Get the token from the request
        $token = $request->bearerToken();

        // Parse the token
        $jwt = \Lcobucci\JWT\Configuration::forSymmetricSigner(
            new \Lcobucci\JWT\Signer\Rsa\Sha256(),
            \Lcobucci\JWT\Signer\Key\InMemory::plainText('empty', 'empty')
        )->parser()->parse($token);

        // Get the sub claim from the token
        $sub = $jwt->claims()->get('sub');

        // Get the notifications
        $notifications = \Illuminate\Notifications\DatabaseNotification::where('notifiable_id', $sub)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        // Return the notifications
        return new \App\Http\Resources\RealDataCollection($notifications);
    }

    /**
     * Mark the notification as read.
     * 
     * @param  string  $id
     * @return \Illuminate\Http\Response
     * */ 
    public function read($id) 
    {
        $notification = \Illuminate\Notifications\DatabaseNotification::find($id);

        if ($notification) {
            $notification->markAsRead();
            return ApiResponse::success('Notification marked as read');
        }

        return ApiResponse::error('Notification not found', 404);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $notification = \Illuminate\Notifications\DatabaseNotification::find($id);

        if ($notification) {
            $notification->delete();
            return ApiResponse::success('Notification deleted successfully');
        }

        return ApiResponse::error('Notification not found', 404);
    }
}
