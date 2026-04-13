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

        return view('dashboard', [
            'guestUploadsUsed' => $guestUploadsUsed,
            'guestUploadsRemaining' => max(0, self::GUEST_UPLOAD_LIMIT - $guestUploadsUsed),
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
        $user = auth()->user();
        $isAuthenticated = $user !== null;

        $guestUploadsUsed = (int) $request->session()->get('guest_upload_attempts', 0);
        $guestSessionId = $isAuthenticated ? null : $request->session()->getId();

        // LIMIT GUEST
        if (!$isAuthenticated && $guestUploadsUsed >= self::GUEST_UPLOAD_LIMIT) {
            return back()->withErrors([
                'upload_limit' => 'Batas upload guest sudah habis. Login untuk upload tanpa batas.',
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

        $bestSimilarity = 0;

        foreach (OfficialContent::all() as $official) {
            similar_text($imageHash, $official->image_hash, $percent);
            $bestSimilarity = max($bestSimilarity, $percent);
        }

        // SIMPAN
        Submission::create([
            'image_path' => $path,
            'image_hash' => $imageHash,
            'extracted_text' => $text,
            'similarity_score' => $bestSimilarity,
            'system_status' => 'diproses',
            'final_status' => 'menunggu_validasi',
            'user_id' => $user?->id,
            'guest_session_id' => $guestSessionId,
            'ip_address' => $request->ip(),
        ]);

        if (!$isAuthenticated) {
            $request->session()->put('guest_upload_attempts', $guestUploadsUsed + 1);
        }

        return back()->with('status', 'Upload berhasil diproses!');
    }
}