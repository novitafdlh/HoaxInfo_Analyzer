<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Facades\Schema;

class NotificationController extends Controller
{
    public function open(Request $request, DatabaseNotification $notification): RedirectResponse
    {
        abort_unless($notification->notifiable_id === $request->user()->id, 403);

        if ($notification->read_at === null) {
            $notification->markAsRead();
        }

        $redirect = (string) $request->query('redirect', route('profile.edit'));

        return redirect()->to($redirect);
    }

    public function markAllRead(Request $request): RedirectResponse
    {
        if (Schema::hasTable('notifications')) {
            $request->user()->unreadNotifications()->update([
                'read_at' => now(),
            ]);
        }

        return back()->with('status', 'Semua notifikasi ditandai sudah dibaca.');
    }
}
