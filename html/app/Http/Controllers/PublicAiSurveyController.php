<?php

namespace App\Http\Controllers;

use App\Models\AiSurvey;
use App\Models\AiSurveyResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PublicAiSurveyController extends Controller
{
    public function show(string $token)
    {
        $survey = AiSurvey::where('token', $token)->with(['document', 'questions'])->firstOrFail();
        $title = $survey->title;

        if (! $survey->isOpen()) {
            return view('ai.survey-closed', compact('title', 'survey'));
        }

        return view('ai.survey-public', compact('title', 'survey'));
    }

    public function submit(Request $request, string $token)
    {
        $survey = AiSurvey::where('token', $token)->with('questions')->firstOrFail();
        if (! $survey->isOpen()) {
            return back()->with('error', 'This survey is closed.');
        }

        $rules = [
            'respondent_name' => 'nullable|string|max:255',
            'respondent_email' => 'nullable|email|max:255',
            'respondent_phone' => 'nullable|string|max:30',
            'respondent_designation' => 'nullable|string|max:255',
        ];

        foreach ($survey->questions as $question) {
            $key = 'answers.' . $question->id;
            $required = $question->is_required ? 'required' : 'nullable';
            $rules[$key] = match ($question->question_type) {
                'rating' => $required . '|integer|min:1|max:5',
                'yes_no' => $required . '|in:yes,no',
                'multiple_choice' => $required . '|string|max:1000',
                default => $required . '|string|max:2000',
            };
        }

        $validated = $request->validate($rules);

        DB::transaction(function () use ($request, $survey, $validated) {
            AiSurveyResponse::create([
                'survey_id' => $survey->id,
                'answers' => $validated['answers'] ?? [],
                'respondent_name' => $request->respondent_name,
                'respondent_email' => $request->respondent_email,
                'respondent_phone' => $request->respondent_phone,
                'respondent_designation' => $request->respondent_designation,
                'ip_address' => $request->ip(),
                'user_agent' => substr((string) $request->userAgent(), 0, 65535),
                'submitted_at' => now(),
            ]);

            $survey->increment('response_count');
        });

        return redirect()->route('ai.survey.public', $survey->token)->with('success', 'Thank you! Your feedback was submitted.');
    }
}
