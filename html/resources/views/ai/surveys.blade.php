@extends('layouts.master')

@section('maincontent')
<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <h4 class="mb-3">AI Surveys</h4>
                </div>
            </div>

            <div class="card mb-3">
                <div class="card-header"><h5 class="mb-0">Generate Survey from Document</h5></div>
                <div class="card-body">
                    <div class="row g-2">
                        @forelse($documents as $document)
                        <div class="col-md-6 col-xl-4">
                            <div class="border rounded p-3 h-100">
                                <div class="fw-semibold">{{ $document->title }}</div>
                                <small class="text-muted">{{ $document->original_filename }}</small>
                                <form action="{{ route('ai.surveys.generate', $document) }}" method="post" class="mt-2">
                                    @csrf
                                    <button class="btn btn-primary btn-sm" type="submit">Generate Draft Survey</button>
                                </form>
                            </div>
                        </div>
                        @empty
                        <div class="col-12 text-muted">No ready documents available. Upload and index documents first.</div>
                        @endforelse
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header"><h5 class="mb-0">Survey List</h5></div>
                <div class="card-body table-responsive">
                    <table class="table table-bordered align-middle">
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Document</th>
                                <th>Status</th>
                                <th>Responses</th>
                                <th>Published</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($surveys as $survey)
                            <tr>
                                <td>{{ $survey->title }}</td>
                                <td>{{ $survey->document?->title ?? '-' }}</td>
                                <td><span class="badge bg-{{ $survey->status === 'published' ? 'success' : ($survey->status === 'closed' ? 'secondary' : 'warning') }}">{{ strtoupper($survey->status) }}</span></td>
                                <td>{{ $survey->response_count }}</td>
                                <td>{{ $survey->published_at?->format('d M Y H:i') ?? '-' }}</td>
                                <td>
                                    <div class="d-flex flex-wrap gap-2">
                                        <a class="btn btn-sm btn-outline-primary" href="{{ route('ai.surveys.edit', $survey) }}">Edit</a>
                                        @if($survey->status !== 'published')
                                        <form action="{{ route('ai.surveys.publish', $survey) }}" method="post" class="d-flex gap-1">
                                            @csrf
                                            <input type="datetime-local" class="form-control form-control-sm" name="expires_at" title="Optional expiry">
                                            <button class="btn btn-sm btn-success">Publish</button>
                                        </form>
                                        @endif
                                        @if($survey->status === 'published')
                                        <form action="{{ route('ai.surveys.close', $survey) }}" method="post">
                                            @csrf
                                            <button class="btn btn-sm btn-warning">Close</button>
                                        </form>
                                        <button class="btn btn-sm btn-info" onclick="navigator.clipboard.writeText('{{ route('ai.survey.public', $survey->token) }}'); toastr.success('Survey link copied');">Copy Link</button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="6" class="text-center text-muted">No surveys yet.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                    {{ $surveys->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
