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
    public function topic_similarity_matches_related_infographic_keywords_despite_noisy_ocr(): void
    {
        $service = new OcrService();

        $noisyOfficialOcr = 'atar proyek pemerin abupa antikorupsi oknum pembagian proyek';
        $circulatingInfographicOcr = 'GEBRAK bongkar semua proyek pemda Parimo dikendalikan avatar rahasia. LSM GEBRAK mengungkap jaringan rahasia yang mengendalikan proyek pemerintah di Kabupaten Parigi Moutong.';

        $this->assertGreaterThanOrEqual(
            30.0,
            $service->topicSimilarityPercent($circulatingInfographicOcr, $noisyOfficialOcr)
        );
    }

    #[Test]
    public function important_token_coverage_detects_same_news_even_when_one_ocr_has_extra_noise(): void
    {
        $service = new OcrService();

        $circulatingOcr = 'Kejati Sulteng selamatkan Rp 27,4 miliar dari perkara korupsi sepanjang 2025. Kejaksaan Tinggi Sulawesi Tengah menyampaikan capaian kinerja Bidang Tindak Pidana Khusus Pidsus bersama seluruh satuan kerja di wilayah hukumnya.';
        $officialOcr = 'Noise lorem ipsum Kejaksaan Tinggi Sulawesi Tengah Kejati Sulteng menyampaikan capaian kinerja Bidang Tindak Pidana Khusus Pidsus bersama seluruh satuan kerja di wilayah hukumnya dalam penanganan perkara tindak pidana korupsi sepanjang tahun 2025 tambahan visual rusak.';

        $this->assertGreaterThanOrEqual(
            85.0,
            $service->importantTokenCoveragePercent($circulatingOcr, $officialOcr)
        );
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

    #[Test]
    public function merge_candidate_texts_keeps_unique_lines_from_multiple_ocr_modes(): void
    {
        $service = new OcrService();
        $mergeMethod = new ReflectionMethod($service, 'mergeCandidateTexts');
        $mergeMethod->setAccessible(true);

        $mergedText = $mergeMethod->invoke($service, "Judul Berita\nFakta Utama", [
            "Judul Berita\nFakta Utama",
            "Fakta Utama\nDetail tambahan dari mode lain",
            "detail tambahan dari mode lain",
        ]);

        $this->assertSame(
            "Judul Berita\nFakta Utama\nDetail tambahan dari mode lain",
            $mergedText
        );
    }
}
