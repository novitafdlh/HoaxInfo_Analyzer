<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Submission extends Model
{
    protected $fillable = [
        'image_path',
        'image_hash',
        'extracted_text',
        'similarity_score',
        'similarity_label',
        'system_status',
        'final_status',
        'user_id',
        'guest_session_id',
        'ip_address',
    ];

    protected $casts = [
        'similarity_score' => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public static function claimGuestSubmissionsToUser(?string $guestSessionId, int $userId): int
    {
        if ($guestSessionId === null || $guestSessionId === '') {
            return 0;
        }

        return self::query()
            ->whereNull('user_id')
            ->where('guest_session_id', $guestSessionId)
            ->update([
                'user_id' => $userId,
                'guest_session_id' => null,
            ]);
    }
}
