<?php

namespace Tests\Unit;

use App\Models\AiSurvey;
use Carbon\Carbon;
use Tests\TestCase;

class AiSurveyTest extends TestCase
{
    public function test_is_open_returns_true_for_valid_published_survey(): void
    {
        $survey = new AiSurvey([
            'status' => 'published',
            'response_count' => 1,
            'max_responses' => 10,
            'expires_at' => Carbon::now()->addHour(),
        ]);

        $this->assertTrue($survey->isOpen());
    }

    public function test_is_open_returns_false_for_expired_or_closed_conditions(): void
    {
        $expired = new AiSurvey([
            'status' => 'published',
            'expires_at' => Carbon::now()->subMinute(),
        ]);
        $this->assertFalse($expired->isOpen());

        $limitReached = new AiSurvey([
            'status' => 'published',
            'response_count' => 5,
            'max_responses' => 5,
        ]);
        $this->assertFalse($limitReached->isOpen());

        $draft = new AiSurvey(['status' => 'draft']);
        $this->assertFalse($draft->isOpen());
    }
}
