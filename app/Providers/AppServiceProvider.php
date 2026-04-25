<?php

namespace App\Providers;

use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('layouts.portal.header', function ($view): void {
            $user = Auth::user();
            $notifications = collect();
            $notificationCount = 0;

            if ($user && Schema::hasTable('notifications')) {
                $notifications = $user->notifications()
                    ->latest()
                    ->limit(8)
                    ->get()
                    ->map(function (DatabaseNotification $notification) {
                        $data = $notification->data;
                        $url = (string) ($data['url'] ?? route('profile.edit'));

                        return [
                            'id' => $notification->id,
                            'title' => $data['title'] ?? 'Notifikasi baru',
                            'message' => $data['message'] ?? 'Ada pembaruan terbaru untuk Anda.',
                            'meta' => $data['meta'] ?? '',
                            'url' => str_replace('__ID__', $notification->id, $url),
                            'tone' => $data['tone'] ?? 'blue',
                            'icon' => $data['icon'] ?? 'notifications',
                            'time' => optional($notification->created_at)->diffForHumans(),
                            'read' => $notification->read_at !== null,
                        ];
                    });

                $notificationCount = $user->unreadNotifications()->count();
            }

            $view->with('headerNotifications', $notifications->values()->all());
            $view->with('headerNotificationCount', $notificationCount);
        });
    }
}
