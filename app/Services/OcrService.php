<?php

namespace App\Services;

use Symfony\Component\Process\Process;

class OcrService
{
    public function extractText(string $absoluteImagePath): ?string
    {
        if (!is_file($absoluteImagePath)) {
            return null;
        }

        $tesseractPath = env('TESSERACT_PATH', 'tesseract');
        $ocrLanguage = env('OCR_LANGUAGE', 'ind+eng');
        $safeLanguage = preg_replace('/[^A-Za-z0-9+_-]/', '', $ocrLanguage) ?: 'ind+eng';
        $psmModes = $this->getPsmModes();
        $preprocessProfiles = $this->getPreprocessProfiles();
        $bestText = null;
        $bestScore = -1;

        foreach ($preprocessProfiles as $profileOptions) {
            foreach ($psmModes as $psmMode) {
                $command = [
                    $tesseractPath,
                    $absoluteImagePath,
                    'stdout',
                    '-l',
                    $safeLanguage,
                    '--oem',
                    '1',
                    '--psm',
                    (string) $psmMode,
                    '-c',
                    'preserve_interword_spaces=1',
                    '-c',
                    'user_defined_dpi=300',
                ];

                foreach ($profileOptions as $option) {
                    $command[] = '-c';
                    $command[] = $option;
                }

                $process = new Process($command);
                $process->setTimeout(25);
                $process->run();

                if (!$process->isSuccessful()) {
                    continue;
                }

                $candidateText = $this->cleanOcrText($process->getOutput());

                if ($candidateText === null) {
                    continue;
                }

                $candidateScore = $this->scoreTextQuality($candidateText);

                if ($candidateScore > $bestScore) {
                    $bestScore = $candidateScore;
                    $bestText = $candidateText;
                }
            }
        }

        return $bestText;
    }

    public function normalizedText(?string $text): string
    {
        if ($text === null) {
            return '';
        }

        $normalized = mb_strtolower($text, 'UTF-8');
        $normalized = preg_replace('/\s+/', ' ', $normalized) ?? '';
        $normalized = preg_replace('/[^\p{L}\p{N}\s]/u', '', $normalized) ?? '';

        return trim($normalized);
    }

    public function similarityPercent(?string $firstText, ?string $secondText): float
    {
        $firstNormalized = $this->normalizedText($firstText);
        $secondNormalized = $this->normalizedText($secondText);

        if ($firstNormalized === '' || $secondNormalized === '') {
            return 0.0;
        }

        if ($firstNormalized === $secondNormalized) {
            return 100.0;
        }

        similar_text($firstNormalized, $secondNormalized, $similarity);

        return round($similarity, 2);
    }

    private function getPsmModes(): array
    {
        $rawModes = (string) env('OCR_PSM_LIST', '6,11,12');
        $modes = array_filter(array_map('trim', explode(',', $rawModes)));
        $validModes = [];

        foreach ($modes as $mode) {
            if (ctype_digit($mode)) {
                $validModes[] = (int) $mode;
            }
        }

        return $validModes !== [] ? array_values(array_unique($validModes)) : [6, 11, 12];
    }

    private function getPreprocessProfiles(): array
    {
        return [
            [],
            [
                'tessedit_do_invert=0',
                'thresholding_method=2',
                'thresholding_window_size=0.33',
                'thresholding_kfactor=0.34',
            ],
            [
                'tessedit_do_invert=1',
                'thresholding_method=1',
                'thresholding_window_size=0.25',
                'thresholding_kfactor=0.40',
            ],
        ];
    }

    private function cleanOcrText(string $rawText): ?string
    {
        $text = str_replace(["\r\n", "\r"], "\n", $rawText);
        $text = preg_replace('/[^\P{C}\n\t]/u', '', $text) ?? '';
        $text = preg_replace('/[ \t]+/', ' ', $text) ?? '';
        $text = preg_replace('/\n{3,}/', "\n\n", $text) ?? '';
        $text = preg_replace('/[^\p{L}\p{N}\p{P}\p{Z}\n]/u', '', $text) ?? '';
        $text = trim($text);

        if ($text === '') {
            return null;
        }

        return $text;
    }

    private function scoreTextQuality(string $text): int
    {
        $lengthScore = mb_strlen($text, 'UTF-8');
        preg_match_all('/\p{L}+/u', $text, $wordMatches);
        preg_match_all('/\p{N}+/u', $text, $numberMatches);
        $wordScore = count($wordMatches[0]) * 12;
        $numberScore = count($numberMatches[0]) * 4;

        return $lengthScore + $wordScore + $numberScore;
    }
}
