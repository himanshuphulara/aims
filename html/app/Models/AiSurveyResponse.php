<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AiSurveyResponse extends Model
{
    use HasFactory;

    protected $fillable = [
        'survey_id',
        'answers',
        'respondent_name',
        'respondent_email',
        'respondent_phone',
        'respondent_designation',
        'ip_address',
        'user_agent',
        'submitted_at',
    ];

    protected $casts = [
        'answers' => 'array',
        'submitted_at' => 'datetime',
    ];

    public function survey()
    {
        return $this->belongsTo(AiSurvey::class, 'survey_id');
    }
}
