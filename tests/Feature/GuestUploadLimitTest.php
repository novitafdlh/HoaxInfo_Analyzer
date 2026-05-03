<?php

namespace Tests\Feature;

use App\Models\GuestUpload;
use App\Models\OfficialContent;
use App\Models\User;
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

    public function test_guest_validation_links_to_original_url_when_match_comes_from_url_reference(): void
    {
        Storage::fake('public');

        $officialContent = $this->officialContent([
            'source_type' => 'url',
            'source_url' => 'https://example.test/berita-asli',
        ]);

        $this->mockSuccessfulAnalysisFor($officialContent);

        $response = $this->from('/dashboard')->post('/dashboard/upload', [
            'image_file' => $this->fakePngUpload(),
        ]);

        $response->assertRedirect('/dashboard');
        $response->assertSessionHas('validation_popup.official_url', 'https://example.test/berita-asli');
        $response->assertSessionHas('validation_popup.official_url_label', 'Buka URL asli');
        $response->assertSessionHas('validation_popup.official_action_type', 'external_url');
    }

    public function test_guest_validation_requires_authentication_for_internal_official_reference(): void
    {
        Storage::fake('public');

        $officialContent = $this->officialContent([
            'source_type' => 'manual',
            'source_url' => null,
        ]);

        $this->mockSuccessfulAnalysisFor($officialContent);

        $response = $this->from('/dashboard')->post('/dashboard/upload', [
            'image_file' => $this->fakePngUpload(),
        ]);

        $response->assertRedirect('/dashboard');
        $response->assertSessionHas('validation_popup.official_action_type', 'auth_required');
    }

    private function officialContent(array $overrides = []): OfficialContent
    {
        $creator = User::factory()->create(['role' => 'admin']);

        return OfficialContent::query()->create(array_merge([
            'title' => 'Referensi Resmi',
            'category' => 'Umum',
            'image_path' => 'official/reference.jpg',
            'image_hash' => 'official-reference-hash',
            'extracted_text' => 'Konten referensi resmi',
            'source_type' => 'manual',
            'source_url' => null,
            'created_by' => $creator->id,
        ], $overrides));
    }

    private function mockSuccessfulAnalysisFor(OfficialContent $officialContent): void
    {
        $fileSecurityService = Mockery::mock(FileSecurityService::class);
        $fileSecurityService->shouldReceive('scanOrFail')->once();
        $this->app->instance(FileSecurityService::class, $fileSecurityService);

        $ocrService = Mockery::mock(OcrService::class);
        $ocrService->shouldReceive('extractText')->once()->andReturn('Konten yang divalidasi');
        $this->app->instance(OcrService::class, $ocrService);

        $verificationService = Mockery::mock(ContentVerificationService::class);
        $verificationService->shouldReceive('analyze')->once()->andReturn([
            'matched_official_content_id' => $officialContent->id,
            'analysis_method' => 'ocr_cosine_similarity',
            'similarity_score' => 90.0,
            'similarity_label' => 'kesesuaian_tinggi',
            'confidence_level' => 'tinggi',
            'confidence_label' => 'Tinggi',
            'system_status' => 'mendekati_valid',
        ]);
        $this->app->instance(ContentVerificationService::class, $verificationService);
    }

    private function fakePngUpload(): UploadedFile
    {
        return UploadedFile::fake()->createWithContent(
            'guest-proof.png',
            base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mP8/x8AAwMCAO+/p9sAAAAASUVORK5CYII=')
        );
    }
}
