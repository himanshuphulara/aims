<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class AiServiceClient
{
    public function __construct(
        private readonly ?string $baseUrl = null,
        private readonly ?int $timeout = null,
    ) {
    }

    private function client()
    {
        return Http::timeout($this->timeout ?? (int) config('ai.request_timeout', 45));
    }

    private function endpoint(string $path): string
    {
        $base = rtrim($this->baseUrl ?? config('ai.service_url', 'http://127.0.0.1:8001'), '/');
        return $base . '/' . ltrim($path, '/');
    }

    public function health(): array
    {
        $response = $this->client()->get($this->endpoint('/health'));
        return $response->json() ?: ['status' => 'error', 'success' => false];
    }

    public function ingest(array $payload): array
    {
        $timeout = (int) config('ai.ingest_timeout', 300);
        $response = $this->client()->timeout($timeout)->post($this->endpoint('/ingest'), $payload);
        return $response->json() ?: ['success' => false, 'error' => 'Empty response from AI service'];
    }

    public function ask(array $payload): array
    {
        $response = $this->client()->post($this->endpoint('/ask'), $payload);
        return $response->json() ?: ['success' => false, 'error' => 'Empty response from AI service'];
    }

    public function generateSurvey(array $payload): array
    {
        $response = $this->client()->post($this->endpoint('/generate-survey'), $payload);
        return $response->json() ?: ['success' => false, 'error' => 'Empty response from AI service'];
    }
}
