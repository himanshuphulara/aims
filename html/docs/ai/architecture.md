# AI Knowledge Module Architecture

## Overview

This module keeps the existing Laravel monolith as the source of truth for auth, permissions, UI, and survey lifecycle, while delegating RAG-heavy tasks to a local Python service running on the same LAN server.

### Components

- Laravel app (`html/`)
  - Document upload metadata
  - Permission checks (Spatie)
  - Ask AI UI and survey workflow
  - Feedback reporting and exports
- Python AI service (`ai_service/`)
  - PDF parsing
  - Chunking + embedding
  - Vector retrieval
  - Answer generation via Ollama
- Ollama (local)
  - Chat model (e.g. `qwen2.5:7b-instruct`)
  - Embedding model (e.g. `nomic-embed-text`)
- Chroma (local disk)
  - Vector collections keyed by `document_id`

## Single-server LAN runtime

- Laravel URL: `http://<server-ip>/`
- AI service URL: `http://127.0.0.1:8001`
- Ollama URL: `http://127.0.0.1:11434`
- MySQL: same DB used by the rest of AIMS

## Internal API contract (Laravel -> Python)

### `GET /health`

Returns service readiness and model status.

```json
{
  "status": "ok",
  "ollama_reachable": true,
  "embedding_model": "nomic-embed-text",
  "chat_model": "qwen2.5:7b-instruct"
}
```

### `POST /ingest`

Index a document for retrieval.

Request:

```json
{
  "document_id": 101,
  "file_path": "/absolute/path/to/storage/app/ai/documents/file.pdf",
  "title": "Unit SOP 2026",
  "metadata": {
    "uploaded_by": 3
  }
}
```

Response:

```json
{
  "success": true,
  "chunks_indexed": 57,
  "message": "Indexed successfully"
}
```

### `POST /ask`

Ask grounded question over selected document scope.

Request:

```json
{
  "question": "What is the approval chain for quarterly audits?",
  "document_ids": [101, 102],
  "top_k": 5,
  "session_id": "web-uuid"
}
```

Response:

```json
{
  "success": true,
  "answer": "According to Unit SOP 2026, ...",
  "confidence": 0.78,
  "citations": [
    {
      "document_id": 101,
      "document_title": "Unit SOP 2026",
      "page": 12,
      "snippet": "Quarterly audit requests must be..."
    }
  ]
}
```

### `POST /generate-survey`

Generate draft survey questions from one indexed document.

Request:

```json
{
  "document_id": 101,
  "question_count": 8
}
```

Response:

```json
{
  "success": true,
  "title": "Stakeholder Feedback - Unit SOP 2026",
  "questions": [
    {
      "question_text": "How clear are the SOP responsibilities?",
      "question_type": "rating",
      "required": true,
      "options": [1, 2, 3, 4, 5]
    }
  ]
}
```

## Failure contract

All non-2xx responses return:

```json
{
  "success": false,
  "error": "Human-readable message"
}
```

Laravel stores the error in `ai_documents.processing_error` or returns toast feedback for ask/survey actions.
