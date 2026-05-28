<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>{{ $title }}</title>
    <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet" />
</head>
<body class="bg-light">
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card shadow-sm">
                    <div class="card-body p-4">
                        <h4>{{ $survey->title }}</h4>
                        <p class="text-muted mb-1">Document: {{ $survey->document?->title ?? '-' }}</p>
                        @if($survey->intro_text)
                            <p>{{ $survey->intro_text }}</p>
                        @endif

                        @if(session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif
                        @if(session('error'))
                            <div class="alert alert-danger">{{ session('error') }}</div>
                        @endif

                        <form method="post" action="{{ route('ai.survey.public.submit', $survey->token) }}">
                            @csrf
                            <div class="row g-2 mb-3">
                                <div class="col-md-6">
                                    <label class="form-label">Name (optional)</label>
                                    <input type="text" class="form-control" name="respondent_name" value="{{ old('respondent_name') }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Designation (optional)</label>
                                    <input type="text" class="form-control" name="respondent_designation" value="{{ old('respondent_designation') }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Email (optional)</label>
                                    <input type="email" class="form-control" name="respondent_email" value="{{ old('respondent_email') }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Phone (optional)</label>
                                    <input type="text" class="form-control" name="respondent_phone" value="{{ old('respondent_phone') }}">
                                </div>
                            </div>

                            @foreach($survey->questions as $question)
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">
                                        {{ $loop->iteration }}. {{ $question->question_text }}
                                        @if($question->is_required)<span class="text-danger">*</span>@endif
                                    </label>

                                    @if($question->question_type === 'rating')
                                        <select class="form-select" name="answers[{{ $question->id }}]" {{ $question->is_required ? 'required' : '' }}>
                                            <option value="">Select rating</option>
                                            @for($i=1; $i<=5; $i++)
                                                <option value="{{ $i }}" @selected(old("answers.{$question->id}") == $i)>{{ $i }}</option>
                                            @endfor
                                        </select>
                                    @elseif($question->question_type === 'yes_no')
                                        <select class="form-select" name="answers[{{ $question->id }}]" {{ $question->is_required ? 'required' : '' }}>
                                            <option value="">Select</option>
                                            <option value="yes" @selected(old("answers.{$question->id}") === 'yes')>Yes</option>
                                            <option value="no" @selected(old("answers.{$question->id}") === 'no')>No</option>
                                        </select>
                                    @elseif($question->question_type === 'multiple_choice' && is_array($question->options))
                                        <select class="form-select" name="answers[{{ $question->id }}]" {{ $question->is_required ? 'required' : '' }}>
                                            <option value="">Select option</option>
                                            @foreach($question->options as $option)
                                                <option value="{{ $option }}" @selected(old("answers.{$question->id}") === $option)>{{ $option }}</option>
                                            @endforeach
                                        </select>
                                    @else
                                        <textarea class="form-control" rows="3" name="answers[{{ $question->id }}]" {{ $question->is_required ? 'required' : '' }}>{{ old("answers.{$question->id}") }}</textarea>
                                    @endif
                                </div>
                            @endforeach

                            <button class="btn btn-primary" type="submit">Submit Feedback</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
