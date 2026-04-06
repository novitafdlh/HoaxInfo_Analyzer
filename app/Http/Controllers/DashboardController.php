<?php

namespace App\Http\Controllers;

use App\Models\OfficialContent;
use App\Models\Submission;
use App\Services\FileSecurityService;
use App\Services\OcrService;
use App\Services\RemoteImageService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class DashboardController extends Controller
{
    private const GUEST_UPLOAD_LIMIT = 3;

    public function index(Request $request): View
    {
        $guestUploadsUsed = (int) $request->session()->get('guest_upload_attempts', 0);
        $guestUploadsRemaining = max(0, self::GUEST_UPLOAD_LIMIT - $guestUploadsUsed);

        return view('dashboard', [
            'guestUploadsUsed' => $guestUploadsUsed,
            'guestUploadsRemaining' => $guestUploadsRemaining,
            'isAuthenticated' => auth()->check(),
        ]);
    }

    public function store(
        Request $request,
        OcrService $ocrService,
        RemoteImageService $remoteImageService,
        FileSecurityService $fileSecurityService
    ): RedirectResponse
    {
        $isAuthenticated = auth()->check();
        $guestUploadsUsed = (int) $request->session()->get('guest_upload_attempts', 0);
        $guestSessionId = $isAuthenticated ? null : $request->session()->getId();

        if (!$isAuthenticated && $guestUploadsUsed >= self::GUEST_UPLOAD_LIMIT) {
            return back()->withErrors([
                'upload_limit' => 'Batas upload guest sudah habis. Login untuk upload tanpa batas.',
            ])->with('notification_type', 'warning');
        }

        $validated = $request->validate([
            'image_file' => ['nullable', 'file', 'image', 'max:102400'],
            'image_url' => ['nullable', 'url', 'max:2048'],
        ]);

        if (empty($validated['image_file']) && empty($validated['image_url'])) {
            throw ValidationException::withMessages([
                'image_file' => 'Upload file gambar atau isi URL gambar terlebih dahulu.',
            ]);
        }

        $path = null;

        if ($request->hasFile('image_file')) {
            $path = $request->file('image_file')->store('validation-submissions', 'public');
        } elseif ($request->filled('image_url')) {
            try {
                $path = $remoteImageService->fetchAndStore(
                    $request->string('image_url')->toString(),
                    'validation-submissions',
                    100 * 1024 * 1024
                );
            } catch (\RuntimeException $exception) {
                throw ValidationException::withMessages([
                    'image_url' => $exception->getMessage(),
                ]);
            }
        }

        if ($path === null) {
            throw ValidationException::withMessages([
                'image_file' => 'Gagal memproses gambar yang dikirim.',
            ]);
        }

        $absolutePath = storage_path('app/public/'.$path);

        try {
            $fileSecurityService->scanOrFail($absolutePath);
        } catch (\RuntimeException $exception) {
            Storage::disk('public')->delete($path);

            throw ValidationException::withMessages([
                'image_file' => $exception->getMessage(),
            ]);
        }

        $imageHash = hash_file('sha256', $absolutePath);
        $submissionExtractedText = $ocrService->extractText($absolutePath);
        $bestSimilarity = 0.0;
        $matchedByText = false;

        foreach (OfficialContent::select('image_hash', 'extracted_text')->get() as $officialContent) {
            if ($officialContent->image_hash === $imageHash) {
                $bestSimilarity = 100.0;
                break;
            }

            similar_text($imageHash, $officialContent->image_hash, $hashSimilarity);
            $bestSimilarity = max($bestSimilarity, round($hashSimilarity, 2));

            $textSimilarity = $ocrService->similarityPercent(
                $submissionExtractedText,
                $officialContent->extracted_text
            );

            if ($textSimilarity >= 90.0) {
                $bestSimilarity = 100.0;
                $matchedByText = true;
                break;
            }

            $bestSimilarity = max($bestSimilarity, $textSimilarity);
        }

        $systemStatus = 'tidak_tervalidasi_kominfo';
        $finalStatus = 'tidak_valid';
        $responseMessage = 'Konten tidak tervalidasi dari Kominfo.';
        $notificationType = 'error';

        if ($bestSimilarity === 100.0) {
            $systemStatus = $matchedByText ? 'valid_berdasarkan_ocr' : 'valid_100_persen';
            $finalStatus = 'terverifikasi';
            $notificationType = 'success';
            $responseMessage = $matchedByText
                ? 'Konten tervalidasi otomatis oleh sistem berdasarkan kecocokan isi teks OCR dengan konten resmi.'
                : 'Konten tervalidasi otomatis oleh sistem karena 100% cocok dengan konten resmi.';
        } elseif ($bestSimilarity >= 50) {
            $systemStatus = 'perlu_validasi_manual';
            $finalStatus = 'menunggu_validasi';
            $notificationType = 'warning';
            $responseMessage = 'Kemiripan konten '.$bestSimilarity.'%. Submission menunggu validasi manual admin.';
        }

        Submission::create([
            'image_path' => $path,
            'image_hash' => $imageHash,
            'extracted_text' => $submissionExtractedText,
            'similarity_score' => $bestSimilarity,
            'similarity_label' => $bestSimilarity === 100.0 ? 'exact_match' : ($bestSimilarity >= 50 ? 'partial_match' : 'no_match'),
            'system_status' => $systemStatus,
            'final_status' => $finalStatus,
            'user_id' => auth()->id(),
            'guest_session_id' => $guestSessionId,
            'ip_address' => $request->ip(),
        ]);

        if (!$isAuthenticated) {
            $request->session()->put('guest_upload_attempts', $guestUploadsUsed + 1);
        }

        if (!$isAuthenticated && $bestSimilarity >= 50 && $bestSimilarity < 100) {
            return back()
                ->with('status', 'Kemiripan konten '.$bestSimilarity.'%. Upload Anda sudah tersimpan. Login terlebih dahulu agar submission ini terhubung ke akun Anda dan Anda bisa menerima hasil validasi admin.')
                ->with('notification_type', 'warning')
                ->with('show_login_link', true);
        }

        return back()
            ->with('status', $responseMessage)
            ->with('notification_type', $notificationType);
    }
}
