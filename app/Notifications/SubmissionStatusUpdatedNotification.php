<?php

namespace App\Notifications;

use App\Models\Submission;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class SubmissionStatusUpdatedNotification extends Notification
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
        $submission = $this->submission->loadMissing('matchedOfficialContent:id,title');

        return [
            'title' => 'Status analisis Anda diperbarui',
            'message' => 'Status terbaru: '.$submission->finalStatusLabel().'.',
            'meta' => $submission->matchedOfficialContent?->title
                ? 'Referensi: '.$submission->matchedOfficialContent->title
                : $submission->systemStatusLabel(),
            'url' => route('notifications.open', ['notification' => '__ID__', 'redirect' => route('user.validation-results')]),
            'tone' => $submission->final_status === 'terverifikasi' ? 'emerald' : 'blue',
            'icon' => $submission->final_status === 'terverifikasi' ? 'verified' : 'fact_check',
            'submission_id' => $submission->id,
        ];
    }
}
