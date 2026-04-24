<?php

namespace App\Services;

use App\Models\OfficialContent;

class ContentVerificationService
{
    public function __construct(private readonly OcrService $ocrService)
    {
    }

    public function analyze(string $imageHash, ?string $extractedText): array
    {
        $officialContents = OfficialContent::query()->get();

        if ($officialContents->isEmpty()) {
            return [
                'matched_official_content_id' => null,
                'analysis_method' => 'referensi_belum_tersedia',
                'similarity_score' => 0.0,
                'similarity_label' => 'belum_ada_pembanding',
                'confidence_level' => 'tidak_tersedia',
                'confidence_label' => 'Belum tersedia',
                'system_status' => 'referensi_belum_tersedia',
            ];
        }

        $hashMatch = $officialContents->firstWhere('image_hash', $imageHash);

        if ($hashMatch !== null) {
            return [
                'matched_official_content_id' => $hashMatch->id,
                'analysis_method' => 'hash_sha256',
                'similarity_score' => 100.0,
                'similarity_label' => 'identik',
                'confidence_level' => 'sangat_tinggi',
                'confidence_label' => 'Sangat tinggi',
                'system_status' => 'terverifikasi_otomatis',
            ];
        }

        $bestMatch = null;
        $bestScore = 0.0;

        foreach ($officialContents as $officialContent) {
            $score = $this->ocrService->cosineSimilarityPercent(
                $extractedText,
                $officialContent->extracted_text
            );

            if ($score > $bestScore) {
                $bestScore = $score;
                $bestMatch = $officialContent;
            }
        }

        [$similarityLabel, $confidenceLevel, $confidenceLabel, $systemStatus] = $this->mapScoreToDecision($bestScore);

        return [
            'matched_official_content_id' => $bestMatch?->id,
            'analysis_method' => 'ocr_cosine_similarity',
            'similarity_score' => round($bestScore, 2),
            'similarity_label' => $similarityLabel,
            'confidence_level' => $confidenceLevel,
            'confidence_label' => $confidenceLabel,
            'system_status' => $systemStatus,
        ];
    }

    private function mapScoreToDecision(float $score): array
    {
        if ($score >= 85) {
            return [
                'kesesuaian_tinggi',
                'tinggi',
                'Tinggi',
                'mendekati_valid',
            ];
        }

        if ($score >= 60) {
            return [
                'butuh_validasi_manual',
                'menengah',
                'Menengah',
                'perlu_validasi_manual',
            ];
        }

        if ($score >= 30) {
            return [
                'tidak_signifikan',
                'rendah',
                'Rendah',
                'waspada',
            ];
        }

        return [
            'tidak_terverifikasi',
            'sangat_rendah',
            'Sangat rendah',
            'tidak_terverifikasi',
        ];
    }
}
