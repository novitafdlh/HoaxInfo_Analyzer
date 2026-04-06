<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use RuntimeException;

class RemoteImageService
{
    public function fetchAndStore(
        string $url,
        string $directory,
        int $maxBytes = 104857600,
        ?array $allowedDomains = null
    ): string
    {
        $normalizedUrl = trim($url);
        $this->guardUrl($normalizedUrl);
        $this->guardAllowedDomains($normalizedUrl, $allowedDomains);
        $this->guardResolvableHost($normalizedUrl);

        $tempFile = tempnam(sys_get_temp_dir(), 'remote_img_');

        if ($tempFile === false) {
            throw new RuntimeException('Gagal menyiapkan file sementara untuk download gambar.');
        }

        $handle = fopen($tempFile, 'wb');

        if ($handle === false) {
            @unlink($tempFile);
            throw new RuntimeException('Gagal membuka file sementara untuk download gambar.');
        }

        $downloadedBytes = 0;
        $curl = curl_init($normalizedUrl);

        if ($curl === false) {
            fclose($handle);
            @unlink($tempFile);
            throw new RuntimeException('Gagal memulai proses download gambar.');
        }

        curl_setopt_array($curl, [
            CURLOPT_FILE => $handle,
            CURLOPT_FOLLOWLOCATION => false,
            CURLOPT_MAXREDIRS => 0,
            CURLOPT_CONNECTTIMEOUT => 8,
            CURLOPT_TIMEOUT => 25,
            CURLOPT_PROTOCOLS => CURLPROTO_HTTP | CURLPROTO_HTTPS,
            CURLOPT_REDIR_PROTOCOLS => CURLPROTO_HTTP | CURLPROTO_HTTPS,
            CURLOPT_SSL_VERIFYPEER => true,
            CURLOPT_SSL_VERIFYHOST => 2,
            CURLOPT_USERAGENT => 'PublicInfoVerificationBot/1.0',
            CURLOPT_NOPROGRESS => false,
            CURLOPT_PROGRESSFUNCTION => static function (
                $resource,
                float $downloadTotal,
                float $downloadNow,
                float $uploadTotal,
                float $uploadNow
            ) use (&$downloadedBytes, $maxBytes): int {
                $downloadedBytes = (int) $downloadNow;

                return $downloadedBytes > $maxBytes ? 1 : 0;
            },
        ]);

        $success = curl_exec($curl);
        $httpCode = (int) curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $contentType = (string) curl_getinfo($curl, CURLINFO_CONTENT_TYPE);

        curl_close($curl);
        fclose($handle);

        if ($success === false) {
            @unlink($tempFile);
            throw new RuntimeException(
                $downloadedBytes > $maxBytes
                    ? 'Ukuran gambar dari URL melebihi 100MB.'
                    : 'URL gambar tidak dapat diakses.'
            );
        }

        if ($httpCode < 200 || $httpCode >= 300) {
            @unlink($tempFile);
            throw new RuntimeException('URL gambar merespon dengan status HTTP tidak valid.');
        }

        if (str_starts_with(strtolower($contentType), 'image/') === false) {
            @unlink($tempFile);
            throw new RuntimeException('URL tidak mengarah ke konten gambar yang valid.');
        }

        $fileSize = filesize($tempFile);

        if ($fileSize === false || $fileSize <= 0) {
            @unlink($tempFile);
            throw new RuntimeException('Gambar dari URL kosong atau tidak valid.');
        }

        if ($fileSize > $maxBytes) {
            @unlink($tempFile);
            throw new RuntimeException('Ukuran gambar dari URL melebihi 100MB.');
        }

        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $detectedMime = $finfo ? (string) finfo_file($finfo, $tempFile) : '';

        if ($finfo !== false) {
            finfo_close($finfo);
        }

        $allowedMimeToExt = [
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
            'image/webp' => 'webp',
            'image/gif' => 'gif',
            'image/bmp' => 'bmp',
        ];

        if (!array_key_exists($detectedMime, $allowedMimeToExt)) {
            @unlink($tempFile);
            throw new RuntimeException('Format gambar dari URL tidak didukung.');
        }

        $filename = Str::uuid()->toString().'.'.$allowedMimeToExt[$detectedMime];
        $targetPath = trim($directory, '/').'/'.$filename;
        $readStream = fopen($tempFile, 'rb');

        if ($readStream === false) {
            @unlink($tempFile);
            throw new RuntimeException('Gagal membaca file gambar yang sudah diunduh.');
        }

        Storage::disk('public')->put($targetPath, $readStream);
        fclose($readStream);
        @unlink($tempFile);

        return $targetPath;
    }

    private function guardAllowedDomains(string $url, ?array $allowedDomains): void
    {
        if ($allowedDomains === null || $allowedDomains === []) {
            return;
        }

        $host = strtolower((string) parse_url($url, PHP_URL_HOST));

        foreach ($allowedDomains as $domain) {
            $normalizedDomain = strtolower(trim($domain));

            if ($normalizedDomain === '') {
                continue;
            }

            if ($host === $normalizedDomain || str_ends_with($host, '.'.$normalizedDomain)) {
                return;
            }
        }

        throw new RuntimeException('Domain URL tidak termasuk daftar sumber resmi yang diizinkan.');
    }

    private function guardUrl(string $url): void
    {
        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            throw new RuntimeException('Format URL gambar tidak valid.');
        }

        $parts = parse_url($url);
        $scheme = strtolower((string) ($parts['scheme'] ?? ''));
        $host = (string) ($parts['host'] ?? '');

        if (!in_array($scheme, ['http', 'https'], true) || $host === '') {
            throw new RuntimeException('Hanya URL http/https yang diperbolehkan.');
        }
    }

    private function guardResolvableHost(string $url): void
    {
        $host = (string) parse_url($url, PHP_URL_HOST);

        if ($host === '') {
            throw new RuntimeException('Host URL tidak valid.');
        }

        $ipCandidates = [];

        if (filter_var($host, FILTER_VALIDATE_IP)) {
            $ipCandidates[] = $host;
        } else {
            $v4Records = gethostbynamel($host) ?: [];
            $ipCandidates = array_merge($ipCandidates, $v4Records);

            $dnsAaaa = dns_get_record($host, DNS_AAAA) ?: [];

            foreach ($dnsAaaa as $record) {
                if (!empty($record['ipv6'])) {
                    $ipCandidates[] = $record['ipv6'];
                }
            }
        }

        if ($ipCandidates === []) {
            throw new RuntimeException('Host URL tidak dapat di-resolve.');
        }

        foreach (array_unique($ipCandidates) as $ip) {
            if ($this->isPrivateOrReservedIp($ip)) {
                throw new RuntimeException('URL menuju host internal/pribadi tidak diperbolehkan.');
            }
        }
    }

    private function isPrivateOrReservedIp(string $ip): bool
    {
        if (!filter_var($ip, FILTER_VALIDATE_IP)) {
            return true;
        }

        if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
            return filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) === false;
        }

        if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)) {
            $packed = inet_pton($ip);

            if ($packed === false) {
                return true;
            }

            if ($ip === '::1' || $ip === '::') {
                return true;
            }

            $prefix7 = ord($packed[0]) >> 1;
            if ($prefix7 === 0b1111110) {
                return true;
            }

            if ($this->ipv6InPrefix($packed, inet_pton('fe80::'), 10)) {
                return true;
            }
        }

        return false;
    }

    private function ipv6InPrefix(string $candidate, string|false $prefixBase, int $bits): bool
    {
        if ($prefixBase === false) {
            return false;
        }

        $fullBytes = intdiv($bits, 8);
        $remainingBits = $bits % 8;

        if ($fullBytes > 0 && substr($candidate, 0, $fullBytes) !== substr($prefixBase, 0, $fullBytes)) {
            return false;
        }

        if ($remainingBits === 0) {
            return true;
        }

        $mask = 0xFF << (8 - $remainingBits);
        $candidateByte = ord($candidate[$fullBytes]);
        $prefixByte = ord($prefixBase[$fullBytes]);

        return ($candidateByte & $mask) === ($prefixByte & $mask);
    }
}
