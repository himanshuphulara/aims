@extends('layouts.master')

@section('maincontent')
<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <h4 class="mb-3">AI Feedback Dashboard</h4>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <form method="get" class="row g-2">
                        <div class="col-md-3">
                            <select class="form-select" name="status">
                                <option value="">All status</option>
                                @foreach(['draft','published','closed'] as $status)
                                    <option value="{{ $status }}" @selected(request('status') === $status)>{{ ucfirst($status) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button class="btn btn-secondary w-100">Filter</button>
                        </div>
                    </form>
                </div>
                <div class="card-body table-responsive">
                    <table class="table table-bordered align-middle">
                        <thead>
                            <tr>
                                <th>Survey</th>
                                <th>Document</th>
                                <th>Status</th>
                                <th>Response Count</th>
                                <th>Published</th>
                                <th>Export</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($surveys as $survey)
                                <tr>
                                    <td>{{ $survey->title }}</td>
                                    <td>{{ $survey->document?->title ?? '-' }}</td>
                                    <td>{{ strtoupper($survey->status) }}</td>
                                    <td>{{ $survey->response_count }}</td>
                                    <td>{{ $survey->published_at?->format('d M Y H:i') ?? '-' }}</td>
                                    <td>
                                        <a href="{{ route('ai.feedback.export', $survey) }}" class="btn btn-sm btn-outline-primary">Export CSV</a>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="6" class="text-center text-muted">No surveys found.</td></tr>
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
