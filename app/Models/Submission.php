<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Route;

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
        'matched_official_content_id',
        'analysis_method',
        'confidence_level',
        'confidence_label',
        'ip_address',
    ];

    protected $casts = [
        'similarity_score' => 'decimal:2',
    ];

    public function getImageUrlAttribute(): ?string
    {
        if (!$this->image_path) {
            return null;
        }

        return Route::has('media.public')
            ? route('media.public', ['path' => $this->image_path], false)
            : null;
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function matchedOfficialContent(): BelongsTo
    {
        return $this->belongsTo(OfficialContent::class, 'matched_official_content_id');
    }

    public function analysisMethodLabel(): string
    {
        return match ($this->analysis_method) {
            'hash_sha256' => 'Pencocokan hash SHA256',
            'ocr_cosine_similarity' => 'OCR + cosine similarity',
            'referensi_belum_tersedia' => 'Referensi resmi belum tersedia',
            default => 'Metode tidak diketahui',
        };
    }

    public function systemStatusLabel(): string
    {
        return match ($this->system_status) {
            'terverifikasi_otomatis' => 'Terverifikasi otomatis',
            'mendekati_valid' => 'Kesesuaian tinggi',
            'perlu_validasi_manual' => 'Perlu validasi manual',
            'waspada' => 'Perlu diwaspadai',
            'tidak_terverifikasi' => 'Tidak terverifikasi',
            'referensi_belum_tersedia' => 'Referensi belum tersedia',
            default => 'Belum dianalisis',
        };
    }

    public function finalStatusLabel(): string
    {
        return match ($this->final_status) {
            'terverifikasi' => $this->system_status === 'terverifikasi_otomatis' || (float) $this->similarity_score >= 85
                ? 'Terverifikasi sistem'
                : 'Terverifikasi admin',
            'perlu_tindak_lanjut', 'tidak_valid' => 'Perlu tindak lanjut',
            default => 'Menunggu validasi admin',
        };
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
