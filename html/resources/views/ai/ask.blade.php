@extends('layouts.master')

@section('maincontent')
<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <h4 class="mb-3">Ask AI (RAG from Uploaded Documents)</h4>
                    @if($documents->isEmpty())
                    <div class="alert alert-warning">
                        No indexed documents yet. Upload a PDF under
                        <a href="{{ route('ai.documents') }}">AI Knowledge → Documents</a>
                        and wait until status is <strong>READY</strong>.
                    </div>
                    @endif
                </div>
            </div>

            <div class="row">
                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-header"><h5 class="mb-0">Query Scope</h5></div>
                        <div class="card-body">
                            <label class="form-label">Select Documents</label>
                            <select id="document_ids" class="form-select" multiple size="12">
                                @foreach($documents as $document)
                                    <option value="{{ $document->id }}">{{ $document->title }}</option>
                                @endforeach
                            </select>
                            <small class="text-muted">Leave empty to search all indexed documents.</small>
                        </div>
                    </div>
                </div>
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-header"><h5 class="mb-0">Ask</h5></div>
                        <div class="card-body">
                            <div class="mb-3">
                                <textarea id="question" class="form-control" rows="4" placeholder="Type your question here..."></textarea>
                            </div>
                            <button id="askBtn" class="btn btn-primary">Ask AI</button>
                            <span id="askStatus" class="ms-2 text-muted"></span>

                            <hr>
                            <div id="answerWrap" class="d-none">
                                <h6>Answer</h6>
                                <div id="answerText" class="p-3 bg-light rounded"></div>
                                <h6 class="mt-3">Citations</h6>
                                <div id="citations" class="d-flex flex-column gap-2"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    let sessionId = null;

    $('#askBtn').on('click', function () {
        const question = $('#question').val().trim();
        const documentIds = $('#document_ids').val() || [];

        if (!question) {
            toastr.error('Please enter a question.');
            return;
        }

        $('#askBtn').prop('disabled', true);
        $('#askStatus').text('Thinking...');
        $('#answerWrap').addClass('d-none');

        $.ajax({
            url: "{{ route('ai.ask') }}",
            method: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                question: question,
                document_ids: documentIds,
                session_id: sessionId
            },
            success: function (res) {
                sessionId = res.session_id || sessionId;
                $('#answerText').text(res.answer || '');
                $('#citations').empty();
                (res.citations || []).forEach(function (c) {
                    $('#citations').append(
                        `<div class="border rounded p-2">
                            <div class="fw-semibold">${c.document_title || 'Document'} (page ${c.page || '-'})</div>
                            <small class="text-muted">${c.snippet || ''}</small>
                        </div>`
                    );
                });
                $('#answerWrap').removeClass('d-none');
            },
            error: function (xhr) {
                const msg = xhr?.responseJSON?.error || 'AI request failed.';
                toastr.error(msg);
            },
            complete: function () {
                $('#askBtn').prop('disabled', false);
                $('#askStatus').text('');
            }
        });
    });
</script>
@endsection
