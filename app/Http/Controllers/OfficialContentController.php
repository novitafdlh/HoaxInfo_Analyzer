<?php

namespace App\Http\Controllers;

use App\Jobs\ProcessOfficialContentOcrJob;
use App\Models\AuditLog;
use App\Models\OfficialContent;
use App\Services\FileSecurityService;
use App\Services\RemoteImageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class OfficialContentController extends Controller
{
    public function index(Request $request)
    {
        $categories = OfficialContent::query()
            ->select('category')
            ->whereNotNull('category')
            ->where('category', '!=', '')
            ->distinct()
            ->orderBy('category')
            ->pluck('category');

        $selectedCategory = trim((string) $request->query('category', ''));
        $search = trim((string) $request->query('search', ''));

        $officialContents = OfficialContent::query()
            ->when($selectedCategory !== '', function ($query) use ($selectedCategory) {
                $query->where('category', $selectedCategory);
            })
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($subQuery) use ($search) {
                    $subQuery
                        ->where('title', 'like', '%'.$search.'%')
                        ->orWhere('category', 'like', '%'.$search.'%');
                });
            })
            ->latest()
            ->get();

        return view('official.index', [
            'officialContents' => $officialContents,
            'categories' => $categories,
            'selectedCategory' => $selectedCategory,
            'search' => $search,
        ]);
    }

    public function create()
    {
        $categories = OfficialContent::query()
            ->select('category')
            ->whereNotNull('category')
            ->where('category', '!=', '')
            ->distinct()
            ->orderBy('category')
            ->pluck('category');

        return view('official.create', [
            'categories' => $categories,
        ]);
    }

    public function store(
        Request $request,
        RemoteImageService $remoteImageService,
        FileSecurityService $fileSecurityService
    )
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string|max:100',
            'category_new' => 'required_if:category,__new__|nullable|string|max:100',
            'image' => 'nullable|image|max:102400|required_without:image_url',
            'image_url' => 'nullable|url|required_without:image',
        ]);

        $category = $request->input('category') === '__new__'
            ? trim((string) $request->input('category_new'))
            : trim((string) $request->input('category'));

        if ($category === '' || $category === '__new__') {
            return back()
                ->withInput()
                ->withErrors(['category' => 'Kategori konten wajib dipilih atau ditambahkan.']);
        }

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
        $fullPath = Storage::disk('public')->path($path);

        try {
            $fileSecurityService->scanOrFail($fullPath);
        } catch (\RuntimeException $exception) {
            Storage::disk('public')->delete($path);

            return back()->withInput()->with('error', $exception->getMessage());
        }

        $hash = hash_file('sha256', $fullPath);

        // === SAVE TO DB ===
        $official = OfficialContent::create([
            'title' => $request->title,
            'category' => $category,
            'image_path' => $path,
            'image_hash' => $hash,
            'extracted_text' => null,
            'source_type' => $sourceType,
            'source_url' => $sourceUrl,
            'created_by' => auth()->id(),
        ]);

        // === AUDIT LOG ===
        AuditLog::create([
            'action_type' => 'upload_official',
            'reference_type' => 'official',
            'reference_id' => $official->id,
            'performed_by' => auth()->id(),
            'hash_snapshot' => $hash,
        ]);

        if (filter_var(env('OFFICIAL_OCR_ASYNC', false), FILTER_VALIDATE_BOOLEAN)) {
            ProcessOfficialContentOcrJob::dispatch($official->id);
        } else {
            ProcessOfficialContentOcrJob::dispatchSync($official->id);
            $official->refresh();
        }

        return redirect()
            ->route('official.index')
            ->with('success', 'Konten resmi berhasil ditambahkan dan siap dipakai sebagai referensi validasi.')
            ->with('upload_result', [
                'title' => $official->title,
                'category' => $official->category ?: 'Umum',
                'image_url' => asset('storage/'.$official->image_path),
                'image_hash' => $official->image_hash,
                'source_type_label' => $official->source_type === 'url' ? 'URL resmi' : 'Unggah manual',
                'source_url' => $official->source_url,
                'created_at_label' => $official->created_at?->format('d M Y, H:i'),
                'delete_url' => route('official.destroy', $official),
            ]);
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
