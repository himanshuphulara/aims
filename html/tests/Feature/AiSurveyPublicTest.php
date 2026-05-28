<?php

namespace Tests\Feature;

use App\Models\AiDocument;
use App\Models\AiSurvey;
use App\Models\AiSurveyQuestion;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AiSurveyPublicTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_survey_page_loads_for_published_survey(): void
    {
        $survey = $this->makeSurvey('published');

        $response = $this->get(route('ai.survey.public', $survey->token));

        $response->assertOk();
        $response->assertSee($survey->title);
        $response->assertSee('Submit Feedback');
    }

    public function test_public_survey_submission_persists_response(): void
    {
        $survey = $this->makeSurvey('published');
        $question = $survey->questions()->first();

        $response = $this->post(route('ai.survey.public.submit', $survey->token), [
            'respondent_name' => 'Test Officer',
            'answers' => [
                $question->id => 'yes',
            ],
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('ai_survey_responses', [
            'survey_id' => $survey->id,
            'respondent_name' => 'Test Officer',
        ]);
        $this->assertDatabaseHas('ai_surveys', [
            'id' => $survey->id,
            'response_count' => 1,
        ]);
    }

    public function test_closed_survey_shows_closed_message(): void
    {
        $survey = $this->makeSurvey('closed');

        $response = $this->get(route('ai.survey.public', $survey->token));

        $response->assertOk();
        $response->assertSee('currently closed');
    }

    private function makeSurvey(string $status): AiSurvey
    {
        $user = User::factory()->create();
        $document = AiDocument::create([
            'user_id' => $user->id,
            'title' => 'Policy Handbook',
            'description' => 'Test policy',
            'original_filename' => 'policy.pdf',
            'storage_path' => 'ai/documents/policy.pdf',
            'mime_type' => 'application/pdf',
            'size_bytes' => 1234,
            'sha256' => str_repeat('a', 64),
            'status' => 'ready',
        ]);

        $survey = AiSurvey::create([
            'document_id' => $document->id,
            'user_id' => $user->id,
            'title' => 'Stakeholder Feedback',
            'status' => $status,
            'token' => 'token-' . uniqid(),
            'published_at' => $status === 'published' ? now() : null,
            'closed_at' => $status === 'closed' ? now() : null,
        ]);

        AiSurveyQuestion::create([
            'survey_id' => $survey->id,
            'question_text' => 'Was the document useful?',
            'question_type' => 'yes_no',
            'is_required' => true,
            'sort_order' => 1,
            'options' => ['yes', 'no'],
        ]);

        return $survey->fresh('questions');
    }
}
