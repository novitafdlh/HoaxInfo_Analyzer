<?php

namespace App\Services;

use RuntimeException;
use Symfony\Component\Process\Process;

class FileSecurityService
{
    public function scanOrFail(string $absolutePath): void
    {
        if (!is_file($absolutePath)) {
            throw new RuntimeException('File tidak ditemukan untuk proses keamanan.');
        }

        if (!$this->isScanEnabled()) {
            return;
        }

        $clamPath = (string) env('CLAMSCAN_PATH', 'clamscan');
        $process = new Process([
            $clamPath,
            '--no-summary',
            '--infected',
            $absolutePath,
        ]);
        $process->setTimeout(30);
        $process->run();

        $exitCode = $process->getExitCode();
        $output = trim($process->getOutput().' '.$process->getErrorOutput());

        if ($exitCode === 0) {
            return;
        }

        if ($exitCode === 1) {
            throw new RuntimeException('File terdeteksi berbahaya oleh antivirus dan ditolak.');
        }

        throw new RuntimeException(
            'Gagal menjalankan pemeriksaan antivirus. '.$output
        );
    }

    private function isScanEnabled(): bool
    {
        return filter_var((string) env('ENABLE_ANTIVIRUS_SCAN', 'false'), FILTER_VALIDATE_BOOLEAN);
    }
}
