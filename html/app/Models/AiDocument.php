<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AiDocument extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'original_filename',
        'storage_path',
        'mime_type',
        'size_bytes',
        'sha256',
        'status',
        'pages_count',
        'chunks_count',
        'processing_error',
        'indexed_at',
        'last_asked_at',
        'metadata',
    ];

    protected $casts = [
        'metadata' => 'array',
        'indexed_at' => 'datetime',
        'last_asked_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function surveys()
    {
        return $this->hasMany(AiSurvey::class, 'document_id');
    }

}
