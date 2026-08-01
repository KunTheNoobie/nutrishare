<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /** Display user's notifications. */
    public function index()
    {
        $notifications = Auth::user()->systemNotifications()->paginate(15);
        return view('notifications.index', compact('notifications'));
    }

    /** Mark a notification as read. */
    public function markAsRead(Notification $notification)
    {
        if ($notification->user_id === Auth::id()) {
            $notification->update(['is_read' => true]);
        }

        if (request()->wantsJson()) {
            return response()->json(['success' => true]);
        }

        return back()->with('success', 'Notification marked as read.');
    }

    /** Mark all notifications as read. */
    public function markAllAsRead()
    {
        Auth::user()->systemNotifications()->where('is_read', false)->update(['is_read' => true]);

        if (request()->wantsJson()) {
            return response()->json(['success' => true]);
        }

        return back()->with('success', 'All notifications marked as read.');
    }
}
