<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Notification;

class NotificationsController extends Controller
{
    public function me(Request $request)
    {
        $notifications = $request->user()->notifications;
        $data = [];
        foreach ($notifications as $notification) {
            if (!$notification->read_at) {
                $data['unread_notifications'][]['data'] = $notification->data;
            } else {
                $data['read_notifications'][]['data'] = $notification->data;
            }
        }
        $request->user()->unreadNotifications->markAsRead();
        return view('notifications.me', $data);
    }
}
