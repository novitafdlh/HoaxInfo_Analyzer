<?php

namespace App\Jobs;

use App\Models\OfficialContent;
use App\Services\OcrService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

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

        $absolutePath = storage_path('app/public/'.$officialContent->image_path);

        if (!is_file($absolutePath)) {
            return;
        }

        $extractedText = $ocrService->extractText($absolutePath);

        $officialContent->update([
            'extracted_text' => $extractedText,
        ]);
    }
}
