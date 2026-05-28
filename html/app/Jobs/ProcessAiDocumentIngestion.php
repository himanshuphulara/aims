<?php

namespace App\Jobs;

use App\Models\AiDocument;
use App\Services\AiServiceClient;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class ProcessAiDocumentIngestion implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(public int $documentId)
    {
    }

    /**
     * Execute the job.
     */
    public function handle(AiServiceClient $client): void
    {
        $document = AiDocument::find($this->documentId);
        if (! $document) {
            return;
        }

        $document->update([
            'status' => 'processing',
            'processing_error' => null,
        ]);

        try {
            $payload = [
                'document_id' => $document->id,
                'file_path' => storage_path('app/' . $document->storage_path),
                'title' => $document->title,
                'metadata' => [
                    'uploaded_by' => $document->user_id,
                ],
            ];

            $result = $client->ingest($payload);

            if (! ($result['success'] ?? false)) {
                $document->update([
                    'status' => 'failed',
                    'processing_error' => $result['error'] ?? 'Failed to index document',
                ]);
                return;
            }

            $document->update([
                'status' => 'ready',
                'chunks_count' => $result['chunks_indexed'] ?? $document->chunks_count,
                'pages_count' => $result['pages_count'] ?? $document->pages_count,
                'indexed_at' => now(),
                'processing_error' => null,
            ]);
        } catch (\Throwable $e) {
            Log::error('AI ingest job failed', [
                'document_id' => $document->id,
                'error' => $e->getMessage(),
            ]);

            $document->update([
                'status' => 'failed',
                'processing_error' => $e->getMessage(),
            ]);
        }
    }
}
