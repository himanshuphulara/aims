@extends('layouts.master')

@section('maincontent')
<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12 d-flex justify-content-between align-items-center">
                    <h4 class="mb-3">AI Diagnostics</h4>
                    <a href="{{ route('ai.documents') }}" class="btn btn-light">Back to Documents</a>
                </div>
            </div>

            <div class="row g-3 mb-3">
                @foreach($stats as $key => $value)
                <div class="col-md-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="text-muted small">{{ str_replace('_', ' ', ucfirst($key)) }}</div>
                            <div class="fs-3 fw-bold">{{ $value }}</div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <div class="alert alert-{{ ($phpUploadLimit ?? '') === '25 MB' || str_contains($phpUploadLimit ?? '', '25') ? 'success' : 'warning' }}">
                PHP upload limit for this web server: <strong>{{ $phpUploadLimit ?? 'unknown' }}</strong>.
                @if(! str_contains($phpUploadLimit ?? '', '25'))
                    Stop the server and run <code>./serve-dev.sh</code> from the <code>html</code> folder (not <code>php artisan serve</code>).
                @endif
            </div>

            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Service Health</h5>
                </div>
                <div class="card-body">
                    <pre class="mb-0">{{ json_encode($health, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</pre>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
