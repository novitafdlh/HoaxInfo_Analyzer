<?php

namespace App\Http\Controllers;

use App\Jobs\ProcessOfficialContentOcrJob;
use Illuminate\Http\Request;
use App\Models\OfficialContent;
use App\Models\AuditLog;
use App\Services\FileSecurityService;
use App\Services\OcrService;
use App\Services\RemoteImageService;
use Illuminate\Support\Facades\Storage;

class OfficialContentController extends Controller
{
    public function index()
    {
        $officialContentsByCategory = OfficialContent::query()
            ->latest()
            ->get()
            ->groupBy(fn (OfficialContent $content) => $content->category ?: 'Umum');

        return view('official.index', [
            'officialContentsByCategory' => $officialContentsByCategory,
        ]);
    }

    public function create()
    {
        return view('official.create');
    }

    public function store(
        Request $request,
        RemoteImageService $remoteImageService,
        FileSecurityService $fileSecurityService,
        OcrService $ocrService
    )
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string|max:100',
            'image' => 'nullable|image|max:102400|required_without:image_url',
            'image_url' => 'nullable|url|required_without:image',
        ]);

        // === HANDLE FILE ===
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $path = $file->store('official', 'public');
            $sourceType = 'manual';
            $sourceUrl = null;
        }

        elseif ($request->filled('image_url')) {
            $allowedDomains = collect(explode(',', (string) env('OFFICIAL_ALLOWED_DOMAINS', '')))
                ->map(fn ($domain) => trim($domain))
                ->filter()
                ->values()
                ->all();

            try {
                $path = $remoteImageService->fetchAndStore(
                    $request->string('image_url')->toString(),
                    'official',
                    100 * 1024 * 1024,
                    $allowedDomains
                );
            } catch (\RuntimeException $exception) {
                return back()->withInput()->with('error', $exception->getMessage());
            }

            $sourceType = 'url';
            $sourceUrl = $request->image_url;
        }

        else {
            return back()->with('error', 'Harus upload file atau isi URL');
        }

        // === HASHING ===
        $fullPath = storage_path('app/public/' . $path);

        try {
            $fileSecurityService->scanOrFail($fullPath);
        } catch (\RuntimeException $exception) {
            Storage::disk('public')->delete($path);

            return back()->withInput()->with('error', $exception->getMessage());
        }

        $hash = hash_file('sha256', $fullPath);

        // === SAVE TO DB ===
        $useAsyncOcr = filter_var((string) env('OFFICIAL_OCR_ASYNC', 'true'), FILTER_VALIDATE_BOOLEAN);
        $official = OfficialContent::create([
            'title' => $request->title,
            'category' => $request->string('category')->toString(),
            'image_path' => $path,
            'image_hash' => $hash,
            'extracted_text' => $useAsyncOcr ? null : $ocrService->extractText($fullPath),
            'source_type' => $sourceType,
            'source_url' => $sourceUrl,
            'created_by' => auth()->id(),
        ]);

        if ($useAsyncOcr) {
            ProcessOfficialContentOcrJob::dispatch($official->id);
        }

        // === AUDIT LOG ===
        AuditLog::create([
            'action_type' => 'upload_official',
            'reference_type' => 'official',
            'reference_id' => $official->id,
            'performed_by' => auth()->id(),
            'hash_snapshot' => $hash,
        ]);

        return redirect()->route('official.index')->with('success', 'Official content berhasil ditambahkan');
    }

    public function destroy(OfficialContent $officialContent)
    {
        if ($officialContent->image_path) {
            Storage::disk('public')->delete($officialContent->image_path);
        }

        AuditLog::create([
            'action_type' => 'delete_official',
            'reference_type' => 'official',
            'reference_id' => $officialContent->id,
            'performed_by' => auth()->id(),
            'hash_snapshot' => $officialContent->image_hash,
        ]);

        $officialContent->delete();

        return redirect()->route('official.index')->with('success', 'Konten resmi berhasil dihapus.');
    }

    public function __construct()
    {
        $this->middleware('auth');
    }
}
