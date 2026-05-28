<?php

return [
    'enabled' => (bool) env('AI_ENABLED', true),
    'service_url' => env('AI_SERVICE_URL', 'http://127.0.0.1:8001'),
    'request_timeout' => (int) env('AI_REQUEST_TIMEOUT', 45),
    'ingest_timeout' => (int) env('AI_INGEST_TIMEOUT', 300),
    'ingest_sync' => (bool) env('AI_INGEST_SYNC', true),
];
