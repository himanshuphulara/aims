# AIMS AI Service (Local RAG)

This service provides local/offline RAG endpoints for the Laravel AI Knowledge module.

## Endpoints

- `GET /health`
- `POST /ingest`
- `POST /ask`
- `POST /generate-survey`

## Setup (Windows or Linux)

1. Install Python 3.10+
2. Install dependencies:

```bash
pip install -r requirements.txt
```

3. Install and run Ollama locally:
   - [https://ollama.com/download](https://ollama.com/download)

4. Pull models:

```bash
ollama pull qwen2.5:7b-instruct
ollama pull nomic-embed-text
```

5. Start API:

```bash
uvicorn main:app --host 0.0.0.0 --port 8001
```

## Environment variables

- `OLLAMA_URL` (default: `http://127.0.0.1:11434`)
- `OLLAMA_CHAT_MODEL` (default: `qwen2.5:7b-instruct`)
- `OLLAMA_EMBED_MODEL` (default: `nomic-embed-text`)
- `VECTOR_DB_PATH` (default: `./data/chroma`)
- `VECTOR_COLLECTION` (default: `ai_documents`)
- `MAX_CONTEXT_CHUNKS` (default: `6`)

