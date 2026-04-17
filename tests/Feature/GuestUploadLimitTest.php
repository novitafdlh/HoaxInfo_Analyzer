<?php

namespace Tests\Feature;

use App\Models\GuestUpload;
use App\Services\ContentVerificationService;
use App\Services\FileSecurityService;
use App\Services\OcrService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Mockery;
use Tests\TestCase;

class GuestUploadLimitTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_dashboard_shows_daily_token_information(): void
    {
        GuestUpload::query()->create(['ip_address' => '127.0.0.1']);
        GuestUpload::query()->create(['ip_address' => '127.0.0.1']);

        $response = $this->get('/dashboard');

        $response->assertOk();
        $response->assertSee('Token Gratis Harian');
        $response->assertSee('sisa dari 3 validasi gratis hari ini', false);
        $response->assertSee('1');
    }

    public function test_guest_is_limited_to_three_uploads_per_day(): void
    {
        Storage::fake('public');

        GuestUpload::query()->create(['ip_address' => '127.0.0.1']);
        GuestUpload::query()->create(['ip_address' => '127.0.0.1']);
        GuestUpload::query()->create(['ip_address' => '127.0.0.1']);

        $fileSecurityService = Mockery::mock(FileSecurityService::class);
        $fileSecurityService->shouldReceive('scanOrFail')->never();
        $this->app->instance(FileSecurityService::class, $fileSecurityService);

        $ocrService = Mockery::mock(OcrService::class);
        $ocrService->shouldReceive('extractText')->never();
        $this->app->instance(OcrService::class, $ocrService);

        $verificationService = Mockery::mock(ContentVerificationService::class);
        $verificationService->shouldReceive('analyze')->never();
        $this->app->instance(ContentVerificationService::class, $verificationService);

        $response = $this->from('/dashboard')->post('/dashboard/upload', [
            'image_file' => UploadedFile::fake()->create('guest-proof.txt', 1, 'text/plain'),
        ]);

        $response->assertRedirect('/dashboard');
        $response->assertSessionHasErrors([
            'upload_limit' => 'Token gratis guest untuk hari ini sudah habis. Silakan login atau coba lagi besok.',
        ]);

        $this->assertDatabaseCount('guest_uploads', 3);
    }
}
