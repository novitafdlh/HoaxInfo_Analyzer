<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
    protected $fillable = [
        'action_type',
        'reference_type',
        'reference_id',
        'performed_by',
        'hash_snapshot'
    ];
}
