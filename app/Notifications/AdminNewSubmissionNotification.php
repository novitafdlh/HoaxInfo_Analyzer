<?php

namespace App\Notifications;

use App\Models\Submission;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class AdminNewSubmissionNotification extends Notification
{
    use Queueable;

    public function __construct(
        private readonly Submission $submission
    ) {
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        $submission = $this->submission->loadMissing(['user:id,name', 'matchedOfficialContent:id,title']);
        $senderName = $submission->user?->name ?: 'Pengguna anonim';

        return [
            'title' => 'Submission baru menunggu review',
            'message' => 'Kiriman dari '.$senderName.' masuk ke antrean validasi.',
            'meta' => $submission->matchedOfficialContent?->title
                ? 'Referensi terdekat: '.$submission->matchedOfficialContent->title
                : 'Belum ada referensi resmi terdekat.',
            'url' => route('notifications.open', ['notification' => '__ID__', 'redirect' => route('admin.submissions.show', $submission)]),
            'tone' => 'amber',
            'icon' => 'notifications_active',
            'submission_id' => $submission->id,
        ];
    }
}
