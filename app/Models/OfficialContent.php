<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Route;

class OfficialContent extends Model
{
    protected $fillable = [
        'title',
        'category',
        'image_path',
        'image_hash',
        'extracted_text',
        'source_type',
        'source_url',
        'created_by'
    ];

    public function getImageUrlAttribute(): ?string
    {
        if (!$this->image_path) {
            return null;
        }

        return Route::has('media.public')
            ? route('media.public', ['path' => $this->image_path], false)
            : null;
    }
}
