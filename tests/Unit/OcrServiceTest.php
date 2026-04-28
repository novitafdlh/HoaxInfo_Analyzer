<?php

namespace Tests\Unit;

use App\Services\OcrService;
use PHPUnit\Framework\Attributes\Test;
use ReflectionMethod;
use Tests\TestCase;

class OcrServiceTest extends TestCase
{
    #[Test]
    public function normalized_text_removes_punctuation_and_normalizes_spacing(): void
    {
        $service = new OcrService();

        $normalized = $service->normalizedText("Halo,\n\nDunia!! 2026");

        $this->assertSame('halo dunia 2026', $normalized);
    }

    #[Test]
    public function cosine_similarity_uses_normalized_tokens(): void
    {
        $service = new OcrService();

        $similarity = $service->cosineSimilarityPercent(
            'Informasi resmi pemerintah daerah',
            'Informasi resmi, pemerintah daerah.'
        );

        $this->assertSame(100.0, $similarity);
    }

    #[Test]
    public function score_text_quality_prefers_clean_text_with_good_confidence(): void
    {
        $service = new OcrService();
        $scoreMethod = new ReflectionMethod($service, 'scoreTextQuality');
        $scoreMethod->setAccessible(true);

        $cleanScore = $scoreMethod->invoke($service, 'Informasi resmi pemerintah daerah tahun 2026', 92.5);
        $noisyScore = $scoreMethod->invoke($service, '!!! inforrrmasi ### ~~', 31.0);

        $this->assertGreaterThan($noisyScore, $cleanScore);
    }

    #[Test]
    public function parse_tsv_confidence_averages_valid_word_rows_only(): void
    {
        $service = new OcrService();
        $parseMethod = new ReflectionMethod($service, 'parseTsvConfidence');
        $parseMethod->setAccessible(true);

        $tsv = implode("\n", [
            "level\tpage_num\tblock_num\tpar_num\tline_num\tword_num\tleft\ttop\twidth\theight\tconf\ttext",
            "5\t1\t1\t1\t1\t1\t0\t0\t100\t20\t95.5\tInformasi",
            "5\t1\t1\t1\t1\t2\t100\t0\t80\t20\t88.5\tresmi",
            "5\t1\t1\t1\t1\t3\t180\t0\t0\t0\t-1\t",
        ]);

        $confidence = $parseMethod->invoke($service, $tsv);

        $this->assertSame(92.0, round($confidence, 1));
    }
}
