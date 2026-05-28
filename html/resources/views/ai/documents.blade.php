@extends('layouts.master')

@section('maincontent')
<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h4 class="mb-0">AI Document Library</h4>
                        <a href="{{ route('ai.diagnostics') }}" class="btn btn-outline-primary btn-sm">Diagnostics</a>
                    </div>
                </div>
            </div>

            @if ($errors->any())
            <div class="alert alert-danger">
                <strong>Upload could not be completed:</strong>
                <ul class="mb-0 mt-2">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Upload PDF</h5>
                </div>
                <div class="card-body">
                    <p class="text-muted small mb-3">
                        Maximum upload size on this server: <strong>{{ number_format($maxUploadKb / 1024, 1) }} MB</strong>
                        @if($maxUploadKb < 10240)
                            — if your PDF is larger, stop the app and run <code>./serve-dev.sh</code> from the <code>html</code> folder.
                        @endif
                        Use a text-based PDF (export or Print to PDF). After status is <strong>READY</strong>, it appears under Ask AI.
                    </p>
                    <form action="{{ route('ai.documents.store') }}" method="post" enctype="multipart/form-data" class="row g-3" id="ai-upload-form">
                        @csrf
                        <div class="col-md-4">
                            <label class="form-label">Title</label>
                            <input type="text" class="form-control" name="title" required value="{{ old('title') }}">
                        </div>
                        <div class="col-md-5">
                            <label class="form-label">Description</label>
                            <input type="text" class="form-control" name="description" value="{{ old('description') }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">PDF File</label>
                            <input type="file" class="form-control" name="document" accept=".pdf" required>
                        </div>
                        <div class="col-12">
                            <button class="btn btn-primary" type="submit" id="ai-upload-btn">Upload &amp; Index</button>
                            <span class="text-muted small ms-2" id="ai-upload-hint" style="display:none;">Indexing… this may take a few minutes for large PDFs.</span>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <form class="row g-2" method="get" action="{{ route('ai.documents') }}">
                        <div class="col-md-4">
                            <input type="text" class="form-control" name="search" placeholder="Search title or filename" value="{{ request('search') }}">
                        </div>
                        <div class="col-md-3">
                            <select name="status" class="form-select">
                                <option value="">All status</option>
                                @foreach(['pending','processing','ready','failed'] as $status)
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
                                <th>Title</th>
                                <th>File</th>
                                <th>Status</th>
                                <th>Chunks</th>
                                <th>Uploaded By</th>
                                <th>Updated</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($documents as $document)
                            <tr>
                                <td>
                                    <div class="fw-semibold">{{ $document->title }}</div>
                                    @if($document->description)
                                        <small class="text-muted">{{ $document->description }}</small>
                                    @endif
                                    @if($document->processing_error)
                                        <div><small class="text-danger">{{ $document->processing_error }}</small></div>
                                    @endif
                                </td>
                                <td>{{ $document->original_filename }}</td>
                                <td>
                                    <span class="badge bg-{{ $document->status === 'ready' ? 'success' : ($document->status === 'failed' ? 'danger' : 'warning') }}">
                                        {{ strtoupper($document->status) }}
                                    </span>
                                </td>
                                <td>{{ $document->chunks_count ?? '-' }}</td>
                                <td>{{ $document->user?->name ?? 'System' }}</td>
                                <td>{{ $document->updated_at?->format('d M Y H:i') }}</td>
                                <td>
                                    <div class="d-flex gap-2">
                                        @if($document->status === 'failed')
                                        <form action="{{ route('ai.documents.retry', $document) }}" method="post">
                                            @csrf
                                            <button class="btn btn-sm btn-warning" type="submit">Retry</button>
                                        </form>
                                        @endif
                                        <form action="{{ route('ai.documents.delete', $document) }}" method="post" onsubmit="return confirm('Delete this document?');">
                                            @csrf
                                            @method('delete')
                                            <button class="btn btn-sm btn-danger" type="submit">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted">No documents found.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                    {{ $documents->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.getElementById('ai-upload-form')?.addEventListener('submit', function () {
        const btn = document.getElementById('ai-upload-btn');
        const hint = document.getElementById('ai-upload-hint');
        if (btn) {
            btn.disabled = true;
            btn.textContent = 'Indexing…';
        }
        if (hint) {
            hint.style.display = 'inline';
        }
    });
</script>
@endsection
