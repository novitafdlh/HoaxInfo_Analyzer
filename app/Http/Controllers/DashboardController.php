<?php

namespace App\Http\Controllers;

use App\Models\GuestUpload;
use App\Models\OfficialContent;
use App\Models\Submission;
use App\Models\User;
use App\Notifications\AdminNewSubmissionNotification;
use App\Services\ContentVerificationService;
use App\Services\FileSecurityService;
use App\Services\OcrService;
use App\Services\RemoteImageService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class DashboardController extends Controller
{
    private const GUEST_UPLOAD_LIMIT = 3;
    private const AUTO_VERIFIED_SIMILARITY_THRESHOLD = 85.0;

    public function index(Request $request): View
    {
        $isAuthenticated = auth()->check();
        $guestUploadsUsed = $isAuthenticated ? 0 : $this->guestUploadsUsedToday($request->ip());
        $guestLimitEnabled = $this->isGuestUploadLimitEnabled();
        $guestUploadLimit = $this->guestUploadLimit();

        return view('dashboard', [
            'guestUploadsUsed' => $guestUploadsUsed,
            'guestUploadsRemaining' => $guestLimitEnabled ? max(0, $guestUploadLimit - $guestUploadsUsed) : null,
            'guestUploadLimit' => $guestUploadLimit,
            'guestUploadLimitEnabled' => $guestLimitEnabled,
            'isAuthenticated' => $isAuthenticated,
        ]);
    }

    public function store(
        Request $request,
        RemoteImageService $remoteImageService,
        FileSecurityService $fileSecurityService,
        OcrService $ocrService,
        ContentVerificationService $contentVerificationService
    ): RedirectResponse
    {
        $user = auth()->user();
        $isAuthenticated = $user !== null;

        $guestUploadsUsed = $isAuthenticated ? 0 : $this->guestUploadsUsedToday($request->ip());
        $guestSessionId = $isAuthenticated ? null : $request->session()->getId();
        $guestUploadLimit = $this->guestUploadLimit();

        // LIMIT GUEST
        if (!$isAuthenticated && $this->isGuestUploadLimitEnabled() && $guestUploadsUsed >= $guestUploadLimit) {
            return back()->withErrors([
                'upload_limit' => "Token gratis guest untuk hari ini sudah habis. Batas harian saat ini {$guestUploadLimit} upload. Silakan login atau coba lagi besok.",
            ]);
        }

        // VALIDASI
        $validated = $request->validate([
            'image_file' => ['nullable', 'file', 'image', 'max:102400'],
            'image_url' => ['nullable', 'url', 'max:2048'],
        ]);

        if (empty($validated['image_file']) && empty($validated['image_url'])) {
            throw ValidationException::withMessages([
                'image_file' => 'Upload file atau URL terlebih dahulu.',
            ]);
        }

        // SIMPAN FILE
        if ($request->hasFile('image_file')) {
            $path = $request->file('image_file')->store('validation-submissions', 'public');
        } else {
            $path = $remoteImageService->fetchAndStore(
                $request->image_url,
                'validation-submissions',
                100 * 1024 * 1024
            );
        }

        $absolutePath = storage_path('app/public/'.$path);

        // SCAN
        $fileSecurityService->scanOrFail($absolutePath);

        // HASH & OCR
        $imageHash = hash_file('sha256', $absolutePath);
        $text = $ocrService->extractText($absolutePath);

        $analysis = $contentVerificationService->analyze($imageHash, $text);

        // SIMPAN
        $finalStatus = $this->determineFinalStatus(
            (float) $analysis['similarity_score'],
            (string) $analysis['system_status']
        );

        $submission = Submission::create([
            'image_path' => $path,
            'image_hash' => $imageHash,
            'extracted_text' => $text,
            'similarity_score' => $analysis['similarity_score'],
            'similarity_label' => $analysis['similarity_label'],
            'system_status' => $analysis['system_status'],
            'final_status' => $finalStatus,
            'user_id' => $user?->id,
            'guest_session_id' => $guestSessionId,
            'matched_official_content_id' => $analysis['matched_official_content_id'],
            'analysis_method' => $analysis['analysis_method'],
            'confidence_level' => $analysis['confidence_level'],
            'confidence_label' => $analysis['confidence_label'],
            'ip_address' => $request->ip(),
        ]);

        if (!$isAuthenticated) {
            GuestUpload::create([
                'ip_address' => (string) $request->ip(),
            ]);
        }

        if (Schema::hasTable('notifications')) {
            User::query()
                ->where('role', 'admin')
                ->get()
                ->each(fn (User $admin) => $admin->notify(new AdminNewSubmissionNotification($submission)));
        }

        $matchedOfficialContent = $analysis['matched_official_content_id'] !== null
            ? OfficialContent::find($analysis['matched_official_content_id'])
            : null;

        $officialUrl = null;
        $officialUrlLabel = null;

        if ($matchedOfficialContent !== null) {
            $officialUrl = $matchedOfficialContent->source_url ?: route('user.official.show', $matchedOfficialContent);
            $officialUrlLabel = $matchedOfficialContent->source_url ? 'Buka sumber resmi' : 'Buka detail konten resmi';
        }

        return back()
            ->with('status', 'Upload berhasil dianalisis. Silakan lihat tingkat kemiripan dan confidence system pada hasil verifikasi.')
            ->with('validation_popup', [
                'submission_id' => $submission->id,
                'similarity_score' => (float) $analysis['similarity_score'],
                'confidence_label' => $analysis['confidence_label'],
                'system_status_label' => $submission->systemStatusLabel(),
                'final_status_label' => $submission->finalStatusLabel(),
                'analysis_method_label' => $submission->analysisMethodLabel(),
                'official_title' => $matchedOfficialContent?->title,
                'official_url' => $officialUrl,
                'official_url_label' => $officialUrlLabel,
            ]);
    }

    private function guestUploadsUsedToday(?string $ipAddress): int
    {
        if ($ipAddress === null || $ipAddress === '') {
            return 0;
        }

        return GuestUpload::query()
            ->where('ip_address', $ipAddress)
            ->whereDate('created_at', now()->toDateString())
            ->count();
    }

    private function determineFinalStatus(float $similarityScore, string $systemStatus): string
    {
        if ($systemStatus === 'terverifikasi_otomatis' || $similarityScore >= self::AUTO_VERIFIED_SIMILARITY_THRESHOLD) {
            return 'terverifikasi';
        }

        return 'menunggu_validasi';
    }

    private function isGuestUploadLimitEnabled(): bool
    {
        $configured = env('GUEST_UPLOAD_LIMIT_ENABLED');

        if ($configured === null) {
            return !app()->environment('local');
        }

        return filter_var($configured, FILTER_VALIDATE_BOOLEAN);
    }

    private function guestUploadLimit(): int
    {
        return max(1, (int) env('GUEST_UPLOAD_LIMIT', self::GUEST_UPLOAD_LIMIT));
    }
}
