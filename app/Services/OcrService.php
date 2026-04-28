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

        $tesseractPath = $this->tesseractPath();
        $safeLanguage = $this->ocrLanguage();
        $psmModes = $this->getPsmModes();
        $oemModes = $this->getOemModes();
        $preprocessProfiles = $this->getPreprocessProfiles();
        $bestText = null;
        $bestScore = -INF;

        foreach ($preprocessProfiles as $profileOptions) {
            foreach ($oemModes as $oemMode) {
                foreach ($psmModes as $psmMode) {
                    $candidateText = $this->runTesseract(
                        $this->buildCommand($tesseractPath, $absoluteImagePath, $safeLanguage, $oemMode, $psmMode, $profileOptions)
                    );

                    if ($candidateText === null) {
                        continue;
                    }

                    $cleanedText = $this->cleanOcrText($candidateText);

                    if ($cleanedText === null) {
                        continue;
                    }

                    $tsvOutput = $this->runTesseract(
                        $this->buildCommand(
                            $tesseractPath,
                            $absoluteImagePath,
                            $safeLanguage,
                            $oemMode,
                            $psmMode,
                            $profileOptions,
                            ['tsv']
                        )
                    );
                    $confidence = $this->parseTsvConfidence($tsvOutput);
                    $candidateScore = $this->scoreTextQuality($cleanedText, $confidence);

                    if ($candidateScore > $bestScore) {
                        $bestScore = $candidateScore;
                        $bestText = $cleanedText;
                    }
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

    public function cosineSimilarityPercent(?string $firstText, ?string $secondText): float
    {
        $firstVector = $this->wordFrequencyVector($this->normalizedText($firstText));
        $secondVector = $this->wordFrequencyVector($this->normalizedText($secondText));

        if ($firstVector === [] || $secondVector === []) {
            return 0.0;
        }

        $dotProduct = 0.0;

        foreach ($firstVector as $term => $weight) {
            $dotProduct += $weight * ($secondVector[$term] ?? 0);
        }

        $firstMagnitude = sqrt(array_sum(array_map(
            static fn (int $weight): int => $weight * $weight,
            $firstVector
        )));
        $secondMagnitude = sqrt(array_sum(array_map(
            static fn (int $weight): int => $weight * $weight,
            $secondVector
        )));

        if ($firstMagnitude == 0.0 || $secondMagnitude == 0.0) {
            return 0.0;
        }

        return round(($dotProduct / ($firstMagnitude * $secondMagnitude)) * 100, 2);
    }

    private function getPsmModes(): array
    {
        $rawModes = (string) env('OCR_PSM_LIST', '1,3,4,6,11,12');
        $modes = array_filter(array_map('trim', explode(',', $rawModes)));
        $validModes = [];

        foreach ($modes as $mode) {
            if (ctype_digit($mode)) {
                $validModes[] = (int) $mode;
            }
        }

        return $validModes !== [] ? array_values(array_unique($validModes)) : [1, 3, 4, 6, 11, 12];
    }

    private function getOemModes(): array
    {
        $rawModes = (string) env('OCR_OEM_LIST', '1,3');
        $modes = array_filter(array_map('trim', explode(',', $rawModes)));
        $validModes = [];

        foreach ($modes as $mode) {
            if (ctype_digit($mode) && in_array((int) $mode, [0, 1, 2, 3], true)) {
                $validModes[] = (int) $mode;
            }
        }

        return $validModes !== [] ? array_values(array_unique($validModes)) : [1, 3];
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
            [
                'tessedit_do_invert=0',
                'thresholding_method=0',
            ],
            [
                'tessedit_do_invert=1',
                'thresholding_method=2',
                'thresholding_window_size=0.20',
                'thresholding_kfactor=0.28',
            ],
        ];
    }

    private function cleanOcrText(string $rawText): ?string
    {
        $text = str_replace(["\r\n", "\r"], "\n", $rawText);
        $text = preg_replace('/[^\S\n\t]+/u', ' ', $text) ?? '';
        $text = preg_replace('/[^\P{C}\n\t]/u', '', $text) ?? '';
        $text = preg_replace('/[ \t]+/', ' ', $text) ?? '';
        $text = preg_replace('/\n{3,}/', "\n\n", $text) ?? '';
        $text = preg_replace('/[^\p{L}\p{N}\p{P}\p{Z}\n]/u', '', $text) ?? '';
        $lines = preg_split('/\n/u', $text) ?: [];
        $filteredLines = [];

        foreach ($lines as $line) {
            $trimmedLine = trim($line);

            if ($trimmedLine === '') {
                continue;
            }

            $alnumCount = preg_match_all('/[\p{L}\p{N}]/u', $trimmedLine);
            $symbolCount = preg_match_all('/[^\p{L}\p{N}\s]/u', $trimmedLine);

            if ($alnumCount === 0 && $symbolCount > 0) {
                continue;
            }

            $filteredLines[] = $trimmedLine;
        }

        $text = trim(implode("\n", $filteredLines));

        if ($text === '') {
            return null;
        }

        return $text;
    }

    private function wordFrequencyVector(string $normalizedText): array
    {
        if ($normalizedText === '') {
            return [];
        }

        $terms = preg_split('/\s+/u', $normalizedText, -1, PREG_SPLIT_NO_EMPTY) ?: [];
        $vector = [];

        foreach ($terms as $term) {
            $vector[$term] = ($vector[$term] ?? 0) + 1;
        }

        return $vector;
    }

    private function scoreTextQuality(string $text, float $confidence = 0.0): float
    {
        $lengthScore = mb_strlen($text, 'UTF-8');
        preg_match_all('/\p{L}+/u', $text, $wordMatches);
        preg_match_all('/\p{N}+/u', $text, $numberMatches);
        preg_match_all('/\b[\p{L}\p{N}]{2,}\b/u', $text, $multiCharWordMatches);
        preg_match_all('/[^\p{L}\p{N}\s]{3,}/u', $text, $symbolRuns);
        preg_match_all('/(.)\1{3,}/u', $text, $repeatedCharacters);

        $wordScore = count($wordMatches[0]) * 12;
        $numberScore = count($numberMatches[0]) * 4;
        $multiCharWordScore = count($multiCharWordMatches[0]) * 8;
        $alphaNumericCount = preg_match_all('/[\p{L}\p{N}]/u', $text);
        $textLength = max(1, mb_strlen($text, 'UTF-8'));
        $alphaNumericRatioScore = ($alphaNumericCount / $textLength) * 120;
        $confidenceScore = max(0.0, min(100.0, $confidence)) * 2.2;
        $symbolPenalty = count($symbolRuns[0]) * 18;
        $repeatPenalty = count($repeatedCharacters[0]) * 12;

        return $lengthScore + $wordScore + $numberScore + $multiCharWordScore + $alphaNumericRatioScore + $confidenceScore - $symbolPenalty - $repeatPenalty;
    }

    private function buildCommand(
        string $tesseractPath,
        string $absoluteImagePath,
        string $safeLanguage,
        int $oemMode,
        int $psmMode,
        array $profileOptions,
        array $extraArguments = []
    ): array {
        $command = [
            $tesseractPath,
            $absoluteImagePath,
            'stdout',
            '-l',
            $safeLanguage,
            '--oem',
            (string) $oemMode,
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

        foreach ($extraArguments as $argument) {
            $command[] = $argument;
        }

        return $command;
    }

    private function runTesseract(array $command): ?string
    {
        $process = new Process($command);
        $process->setTimeout($this->timeoutSeconds());
        $process->run();

        if (!$process->isSuccessful()) {
            return null;
        }

        $output = $process->getOutput();

        return $output !== '' ? $output : null;
    }

    private function parseTsvConfidence(?string $tsvOutput): float
    {
        if ($tsvOutput === null || trim($tsvOutput) === '') {
            return 0.0;
        }

        $lines = preg_split('/\r\n|\r|\n/', trim($tsvOutput)) ?: [];
        $confidences = [];

        foreach (array_slice($lines, 1) as $line) {
            $columns = explode("\t", $line);

            if (count($columns) < 12) {
                continue;
            }

            $word = trim((string) end($columns));
            $confidence = (float) $columns[10];

            if ($word === '' || $confidence < 0) {
                continue;
            }

            $confidences[] = $confidence;
        }

        if ($confidences === []) {
            return 0.0;
        }

        return array_sum($confidences) / count($confidences);
    }

    private function tesseractPath(): string
    {
        return (string) env('TESSERACT_PATH', 'tesseract');
    }

    private function ocrLanguage(): string
    {
        $ocrLanguage = (string) env('OCR_LANGUAGE', 'ind+eng');

        return preg_replace('/[^A-Za-z0-9+_-]/', '', $ocrLanguage) ?: 'ind+eng';
    }

    private function timeoutSeconds(): float
    {
        return max(5.0, (float) env('OCR_TIMEOUT_SECONDS', 15));
    }
}
