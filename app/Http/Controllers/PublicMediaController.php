<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class PublicMediaController extends Controller
{
    public function show(string $path): StreamedResponse
    {
        abort_if(
            str_contains($path, '..') || str_starts_with($path, '/'),
            404
        );

        abort_unless(Storage::disk('public')->exists($path), 404);

        return Storage::disk('public')->response($path);
    }
}
