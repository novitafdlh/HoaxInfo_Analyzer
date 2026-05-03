<?php

namespace App\Jobs;

use App\Models\OfficialContent;
use App\Services\OcrService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Storage;

class ProcessOfficialContentOcrJob implements ShouldQueue
{
    use Queueable;

    public function __construct(public int $officialContentId)
    {
    }

    public function handle(OcrService $ocrService): void
    {
        $officialContent = OfficialContent::find($this->officialContentId);

        if (!$officialContent || $officialContent->extracted_text !== null) {
            return;
        }

        $absolutePath = Storage::disk('public')->path($officialContent->image_path);

        if (!is_file($absolutePath)) {
            return;
        }

        $extractedText = $ocrService->extractText($absolutePath);

        $officialContent->update([
            'extracted_text' => filled($extractedText)
                ? $extractedText
                : 'OCR tidak menemukan teks yang dapat dibaca pada gambar ini.',
        ]);
    }
}
