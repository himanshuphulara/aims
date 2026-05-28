<?php

namespace App\Http\Controllers;

use App\Jobs\ProcessAiDocumentIngestion;
use App\Models\AiChatMessage;
use App\Models\AiChatSession;
use App\Models\AiDocument;
use App\Models\AiSurvey;
use App\Services\AiServiceClient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AiKnowledgeController extends Controller
{
    public function documents(Request $request)
    {
        $query = AiDocument::with('user')->orderByDesc('created_at');
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('search')) {
            $search = trim((string) $request->search);
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('original_filename', 'like', "%{$search}%");
            });
        }

        $documents = $query->paginate(15)->withQueryString();
        $title = 'AI Documents';
        $maxUploadKb = $this->maxUploadKilobytes();

        return view('ai.documents', compact('title', 'documents', 'maxUploadKb'));
    }

    public function storeDocument(Request $request)
    {
        $maxUploadKb = $this->maxUploadKilobytes();

        if ($uploadError = $this->documentUploadError($request)) {
            return redirect()
                ->route('ai.documents')
                ->withErrors(['document' => $uploadError])
                ->withInput();
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'document' => 'required|file|mimes:pdf|max:' . max(1, $maxUploadKb),
        ], [
            'document.max' => "PDF must be under {$maxUploadKb} KB (PHP limit). Restart the app with ./serve-dev.sh for 25 MB uploads.",
            'document.uploaded' => 'The PDF could not be uploaded. It may exceed the PHP size limit (' . $this->formatBytes($this->uploadLimitBytes('upload_max_filesize')) . '). Restart with ./serve-dev.sh.',
        ]);

        $file = $request->file('document');
        $storedPath = $file->store('ai/documents');
        $absolutePath = storage_path('app/' . $storedPath);

        $document = AiDocument::create([
            'user_id' => Auth::id(),
            'title' => $request->title,
            'description' => $request->description,
            'original_filename' => $file->getClientOriginalName(),
            'storage_path' => $storedPath,
            'mime_type' => $file->getMimeType() ?: 'application/pdf',
            'size_bytes' => $file->getSize(),
            'sha256' => hash_file('sha256', $absolutePath),
            'status' => 'pending',
            'metadata' => [
                'uploaded_from_ip' => $request->ip(),
            ],
        ]);

        if ((bool) config('ai.ingest_sync', true)) {
            ProcessAiDocumentIngestion::dispatchSync($document->id);
        } else {
            ProcessAiDocumentIngestion::dispatch($document->id);
        }

        $document->refresh();

        if ($document->status === 'ready') {
            return redirect()->route('ai.documents')->with(
                'success',
                "Document indexed successfully ({$document->chunks_count} chunks)."
            );
        }

        if ($document->status === 'failed') {
            return redirect()->route('ai.documents')->with(
                'error',
                'Upload saved but indexing failed: ' . ($document->processing_error ?: 'Unknown error')
            );
        }

        return redirect()->route('ai.documents')->with(
            'success',
            'Document uploaded. Indexing is in progress — refresh this page in a moment.'
        );
    }

    private function maxUploadKilobytes(): int
    {
        return (int) floor(min(
            $this->uploadLimitBytes('upload_max_filesize'),
            $this->uploadLimitBytes('post_max_size')
        ) / 1024);
    }

    private function documentUploadError(Request $request): ?string
    {
        $contentLength = (int) $request->server('CONTENT_LENGTH', 0);
        $postMaxBytes = $this->uploadLimitBytes('post_max_size');

        if ($contentLength > 0 && $contentLength > $postMaxBytes) {
            return 'Upload request is too large for PHP (post_max_size is '
                . $this->formatBytes($postMaxBytes)
                . '). Stop the server and run: ./serve-dev.sh';
        }

        $file = $request->file('document');

        if (! $file) {
            if ($request->filled('title')) {
                return 'No PDF was received. The file may exceed PHP upload_max_filesize ('
                    . $this->formatBytes($this->uploadLimitBytes('upload_max_filesize'))
                    . '). Stop the server and run: ./serve-dev.sh';
            }

            return null;
        }

        if (! $file->isValid()) {
            return $file->getErrorMessage()
                . ' Stop `php artisan serve` and run `./serve-dev.sh` from the html folder for 25 MB uploads.';
        }

        return null;
    }

    private function uploadLimitBytes(string $iniKey): int
    {
        $raw = ini_get($iniKey);
        if ($raw === false || $raw === '') {
            return 2 * 1024 * 1024;
        }

        $value = trim((string) $raw);
        $unit = strtolower(substr($value, -1));
        $number = (float) $value;

        return match ($unit) {
            'g' => (int) ($number * 1024 * 1024 * 1024),
            'm' => (int) ($number * 1024 * 1024),
            'k' => (int) ($number * 1024),
            default => (int) $number,
        };
    }

    private function formatBytes(int $bytes): string
    {
        if ($bytes >= 1024 * 1024) {
            return round($bytes / 1024 / 1024, 1) . ' MB';
        }

        return round($bytes / 1024) . ' KB';
    }

    public function retryDocument(AiDocument $document)
    {
        $document->update([
            'status' => 'pending',
            'processing_error' => null,
        ]);

        if ((bool) config('ai.ingest_sync', true)) {
            ProcessAiDocumentIngestion::dispatchSync($document->id);
        } else {
            ProcessAiDocumentIngestion::dispatch($document->id);
        }

        return redirect()->route('ai.documents')->with('success', 'Document re-indexing started.');
    }

    public function deleteDocument(AiDocument $document)
    {
        if ($document->storage_path && Storage::exists($document->storage_path)) {
            Storage::delete($document->storage_path);
        }

        $document->delete();

        return redirect()->route('ai.documents')->with('success', 'Document deleted.');
    }

    public function askPage()
    {
        $documents = AiDocument::where('status', 'ready')->orderBy('title')->get();
        $title = 'Ask AI';

        return view('ai.ask', compact('title', 'documents'));
    }

    public function ask(Request $request, AiServiceClient $client)
    {
        $request->validate([
            'question' => 'required|string|max:2000',
            'document_ids' => 'nullable|array',
            'document_ids.*' => 'integer|exists:ai_documents,id',
            'session_id' => 'nullable|integer|exists:ai_chat_sessions,id',
        ]);

        $session = null;
        if ($request->filled('session_id')) {
            $session = AiChatSession::where('id', $request->session_id)->where('user_id', Auth::id())->first();
        }
        if (! $session) {
            $session = AiChatSession::create([
                'user_id' => Auth::id(),
                'title' => Str::limit($request->question, 60),
                'last_activity_at' => now(),
            ]);
        }

        AiChatMessage::create([
            'session_id' => $session->id,
            'user_id' => Auth::id(),
            'role' => 'user',
            'content' => $request->question,
        ]);

        $payload = [
            'question' => $request->question,
            'document_ids' => $request->document_ids ?? [],
            'session_id' => (string) $session->id,
            'top_k' => 5,
        ];

        $result = $client->ask($payload);

        if (! ($result['success'] ?? false)) {
            return response()->json([
                'success' => false,
                'error' => $result['error'] ?? 'AI service failed to answer.',
            ], 422);
        }

        AiChatMessage::create([
            'session_id' => $session->id,
            'user_id' => Auth::id(),
            'role' => 'assistant',
            'content' => $result['answer'] ?? 'No answer returned.',
            'citations' => $result['citations'] ?? [],
            'confidence' => $result['confidence'] ?? null,
        ]);

        $session->update(['last_activity_at' => now()]);
        AiDocument::whereIn('id', $request->document_ids ?? [])->update(['last_asked_at' => now()]);

        return response()->json([
            'success' => true,
            'session_id' => $session->id,
            'answer' => $result['answer'] ?? '',
            'citations' => $result['citations'] ?? [],
            'confidence' => $result['confidence'] ?? null,
        ]);
    }

    public function health(AiServiceClient $client)
    {
        return response()->json($client->health());
    }

    public function diagnostics(AiServiceClient $client)
    {
        $title = 'AI Diagnostics';
        $health = $client->health();
        $stats = [
            'documents_total' => AiDocument::count(),
            'documents_ready' => AiDocument::where('status', 'ready')->count(),
            'documents_failed' => AiDocument::where('status', 'failed')->count(),
            'surveys_total' => AiSurvey::count(),
            'surveys_published' => AiSurvey::where('status', 'published')->count(),
        ];
        $phpUploadLimit = $this->formatBytes($this->uploadLimitBytes('upload_max_filesize'));

        return view('ai.diagnostics', compact('title', 'health', 'stats', 'phpUploadLimit'));
    }
}
