<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
}
