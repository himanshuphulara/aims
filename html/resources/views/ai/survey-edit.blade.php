@extends('layouts.master')

@section('maincontent')
<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12 d-flex justify-content-between align-items-center">
                    <h4 class="mb-3">Edit Survey: {{ $survey->title }}</h4>
                    <a href="{{ route('ai.surveys') }}" class="btn btn-light">Back</a>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <form action="{{ route('ai.surveys.update', $survey) }}" method="post">
                        @csrf
                        @method('put')
                        <div class="mb-3">
                            <label class="form-label">Survey Title</label>
                            <input type="text" class="form-control" name="title" value="{{ old('title', $survey->title) }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Intro Text</label>
                            <textarea class="form-control" name="intro_text" rows="3">{{ old('intro_text', $survey->intro_text) }}</textarea>
                        </div>

                        <h5 class="mt-4">Questions</h5>
                        @foreach($survey->questions as $index => $question)
                        <div class="border rounded p-3 mb-3">
                            <input type="hidden" name="questions[{{ $index }}][id]" value="{{ $question->id }}">
                            <div class="row g-2">
                                <div class="col-md-7">
                                    <label class="form-label">Question Text</label>
                                    <input type="text" class="form-control" name="questions[{{ $index }}][question_text]" value="{{ old("questions.$index.question_text", $question->question_text) }}" required>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Type</label>
                                    <select class="form-select" name="questions[{{ $index }}][question_type]">
                                        @foreach(['rating','yes_no','multiple_choice','short_text'] as $type)
                                            <option value="{{ $type }}" @selected(old("questions.$index.question_type", $question->question_type) === $type)>{{ $type }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2 d-flex align-items-end">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="1" name="questions[{{ $index }}][is_required]" @checked(old("questions.$index.is_required", $question->is_required))>
                                        <label class="form-check-label">Required</label>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Options (one per line)</label>
                                    <textarea class="form-control" name="questions[{{ $index }}][options]" rows="3">{{ old("questions.$index.options", is_array($question->options) ? implode("\n", $question->options) : '') }}</textarea>
                                </div>
                            </div>
                        </div>
                        @endforeach

                        <button class="btn btn-primary" type="submit">Save Survey</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
