# AI Knowledge Module - Windows Single-Server Runbook

This runbook starts and verifies Laravel + MySQL + AI service on one LAN server.

## 1) Prerequisites

- Windows Server/Windows Pro machine with static LAN IP
- MySQL 8 running
- PHP 8.2+ and Composer
- Python 3.10+
- Ollama installed

## 2) Laravel configuration

Set in `.env`:

```env
AI_ENABLED=true
AI_SERVICE_URL=http://127.0.0.1:8001
AI_REQUEST_TIMEOUT=45
AI_INGEST_SYNC=true
```

Then run:

```bash
php artisan config:clear
php artisan migrate
php artisan db:seed --class=AiPermissionSeeder
```

## 3) Start Ollama and pull models

```bash
ollama serve
ollama pull qwen2.5:7b-instruct
ollama pull nomic-embed-text
```

## 4) Start Python AI service

From `ai_service/`:

```bash
pip install -r requirements.txt
uvicorn main:app --host 127.0.0.1 --port 8001
```

## 5) Health checks

- Open AIMS and go to `AI Knowledge -> Documents -> Diagnostics`
- Verify:
  - `status` is `ok`
  - `ollama_reachable` is `true`
  - document/survey stats load

Optional direct checks:

```bash
curl http://127.0.0.1:8001/health
curl http://127.0.0.1:8000/ai/health
```

## 6) Smoke test flow

1. Upload one PDF in `AI Knowledge -> Documents`
2. Confirm document status changes to `READY`
3. Ask a question in `AI Knowledge -> Ask AI`
4. Generate draft survey from `AI Knowledge -> Surveys`
5. Publish and open token URL from LAN client
6. Submit one response and export CSV in `AI Knowledge -> Feedback`

## 7) Troubleshooting

- `Document failed` with extraction errors:
  - PDF likely scanned-only/no extractable text; use OCR source.
- AI health degraded:
  - Ensure Ollama service is running and model names match env.
- Slow answers:
  - Reduce `top_k` in AI service or use smaller/faster model.
- Survey generation fails:
  - Verify document is indexed (`status=ready`) before generate.
