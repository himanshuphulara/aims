<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AiSurvey extends Model
{
    use HasFactory;

    protected $fillable = [
        'document_id',
        'user_id',
        'title',
        'intro_text',
        'status',
        'token',
        'published_at',
        'closed_at',
        'expires_at',
        'max_responses',
        'response_count',
        'settings',
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'closed_at' => 'datetime',
        'expires_at' => 'datetime',
        'settings' => 'array',
    ];

    public function document()
    {
        return $this->belongsTo(AiDocument::class, 'document_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function questions()
    {
        return $this->hasMany(AiSurveyQuestion::class, 'survey_id')->orderBy('sort_order');
    }

    public function responses()
    {
        return $this->hasMany(AiSurveyResponse::class, 'survey_id');
    }

    public function isOpen(): bool
    {
        if ($this->status !== 'published') {
            return false;
        }

        if ($this->expires_at && $this->expires_at->isPast()) {
            return false;
        }

        if ($this->max_responses && $this->response_count >= $this->max_responses) {
            return false;
        }

        return true;
    }
}
