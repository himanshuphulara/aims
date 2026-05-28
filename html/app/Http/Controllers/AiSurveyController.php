<?php

namespace App\Http\Controllers;

use App\Models\AiDocument;
use App\Models\AiSurvey;
use App\Models\AiSurveyQuestion;
use App\Services\AiServiceClient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AiSurveyController extends Controller
{
    public function index()
    {
        $surveys = AiSurvey::with(['document', 'user'])->orderByDesc('created_at')->paginate(12);
        $documents = AiDocument::where('status', 'ready')->orderBy('title')->get();
        $title = 'AI Surveys';

        return view('ai.surveys', compact('title', 'surveys', 'documents'));
    }

    public function generateFromDocument(AiDocument $document, AiServiceClient $client)
    {
        if ($document->status !== 'ready') {
            return redirect()->route('ai.surveys')->with('error', 'Document must be indexed before survey generation.');
        }

        $result = $client->generateSurvey([
            'document_id' => $document->id,
            'question_count' => 8,
        ]);

        if (! ($result['success'] ?? false)) {
            return redirect()->route('ai.surveys')->with('error', $result['error'] ?? 'Failed to generate survey draft.');
        }

        $survey = DB::transaction(function () use ($document, $result) {
            $survey = AiSurvey::create([
                'document_id' => $document->id,
                'user_id' => Auth::id(),
                'title' => $result['title'] ?? ('Stakeholder Feedback - ' . $document->title),
                'intro_text' => 'Please share your feedback for this document.',
                'status' => 'draft',
                'token' => Str::random(48),
            ]);

            foreach ($result['questions'] ?? [] as $index => $question) {
                AiSurveyQuestion::create([
                    'survey_id' => $survey->id,
                    'question_text' => $question['question_text'] ?? '',
                    'question_type' => $question['question_type'] ?? 'short_text',
                    'options' => $question['options'] ?? null,
                    'is_required' => (bool) ($question['required'] ?? true),
                    'sort_order' => $index + 1,
                ]);
            }

            return $survey;
        });

        return redirect()->route('ai.surveys.edit', $survey)->with('success', 'Survey draft generated successfully.');
    }

    public function edit(AiSurvey $survey)
    {
        $survey->load(['document', 'questions']);
        $title = 'Edit AI Survey';

        return view('ai.survey-edit', compact('title', 'survey'));
    }

    public function update(Request $request, AiSurvey $survey)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'intro_text' => 'nullable|string',
            'questions' => 'required|array|min:1',
            'questions.*.id' => 'required|integer|exists:ai_survey_questions,id',
            'questions.*.question_text' => 'required|string|max:1000',
            'questions.*.question_type' => 'required|in:rating,yes_no,multiple_choice,short_text',
            'questions.*.is_required' => 'nullable|boolean',
            'questions.*.options' => 'nullable|string',
        ]);

        DB::transaction(function () use ($request, $survey) {
            $survey->update([
                'title' => $request->title,
                'intro_text' => $request->intro_text,
            ]);

            foreach ($request->questions as $index => $questionInput) {
                $question = AiSurveyQuestion::where('survey_id', $survey->id)
                    ->where('id', $questionInput['id'])
                    ->first();

                if (! $question) {
                    continue;
                }

                $options = null;
                if (! empty($questionInput['options'])) {
                    $options = array_values(array_filter(array_map('trim', explode("\n", (string) $questionInput['options']))));
                }

                $question->update([
                    'question_text' => $questionInput['question_text'],
                    'question_type' => $questionInput['question_type'],
                    'is_required' => (bool) ($questionInput['is_required'] ?? false),
                    'sort_order' => $index + 1,
                    'options' => $options,
                ]);
            }
        });

        return redirect()->route('ai.surveys.edit', $survey)->with('success', 'Survey updated.');
    }

    public function publish(Request $request, AiSurvey $survey)
    {
        $request->validate([
            'expires_at' => 'nullable|date',
            'max_responses' => 'nullable|integer|min:1',
        ]);

        $survey->update([
            'status' => 'published',
            'published_at' => now(),
            'closed_at' => null,
            'expires_at' => $request->expires_at ?: null,
            'max_responses' => $request->max_responses ?: null,
        ]);

        return redirect()->route('ai.surveys')->with('success', 'Survey published. Share link: ' . route('ai.survey.public', $survey->token));
    }

    public function close(AiSurvey $survey)
    {
        $survey->update([
            'status' => 'closed',
            'closed_at' => now(),
        ]);

        return redirect()->route('ai.surveys')->with('success', 'Survey closed.');
    }

    public function feedback(Request $request)
    {
        $query = AiSurvey::with(['document', 'responses'])->orderByDesc('created_at');
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        $surveys = $query->paginate(12)->withQueryString();
        $title = 'AI Feedback';

        return view('ai.feedback', compact('title', 'surveys'));
    }

    public function export(AiSurvey $survey): StreamedResponse
    {
        $survey->load(['document', 'questions', 'responses']);
        $filename = 'ai_feedback_' . $survey->id . '_' . now()->format('Ymd_His') . '.csv';

        return response()->streamDownload(function () use ($survey) {
            $out = fopen('php://output', 'w');

            $headers = [
                'survey_id',
                'document_title',
                'submitted_at',
                'respondent_name',
                'respondent_email',
                'respondent_phone',
                'respondent_designation',
            ];
            foreach ($survey->questions as $question) {
                $headers[] = 'Q' . $question->sort_order . ': ' . Str::limit($question->question_text, 80);
            }
            fputcsv($out, $headers);

            foreach ($survey->responses as $response) {
                $answers = $response->answers ?? [];
                $row = [
                    $survey->id,
                    $survey->document?->title ?? '',
                    optional($response->submitted_at)->toDateTimeString(),
                    $response->respondent_name,
                    $response->respondent_email,
                    $response->respondent_phone,
                    $response->respondent_designation,
                ];
                foreach ($survey->questions as $question) {
                    $value = $answers[(string) $question->id] ?? '';
                    if (is_array($value)) {
                        $value = implode(', ', $value);
                    }
                    $row[] = $value;
                }
                fputcsv($out, $row);
            }

            fclose($out);
        }, $filename, [
            'Content-Type' => 'text/csv',
        ]);
    }
}
