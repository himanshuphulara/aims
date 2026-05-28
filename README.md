# AIMS — Accounting & Inventory Management System

**Repository:** [github.com/himanshuphulara/aims](https://github.com/himanshuphulara/aims)

Web application for unit accounting, inventory, vouchers, mess operations, and an optional **AI Knowledge** module (document Q&A, surveys, feedback). The stack is **Laravel 11** (`html/`), **MySQL 8**, and a local **Python FastAPI** service (`ai_service/`) backed by **Ollama** for on-prem RAG.

```bash
git clone https://github.com/himanshuphulara/aims.git
cd aims
```

```
aims/
├── html/                 # Laravel application (document root: html/public)
├── ai_service/           # FastAPI RAG + ingestion (port 8001)
├── scripts/              # Operational helpers (e.g. safe DB re-import)
├── ledgersinfo_html.sql  # Schema + seed data (change passwords after import)
└── README.md
```

**Login:** use **username** (`name` field) and password — not email.

## Features (high level)

- Ledgers, vouchers, categories, inventory, reports, user/role permissions (Spatie)
- Officers Mess / JCO Mess workflows (category names must match exactly)
- Database backup/restore (cross-platform `mysqldump` detection)
- **AI Knowledge** (optional): PDF upload, Ask AI, surveys, public survey links, diagnostics

---

## Requirements

### All environments

| Component | Version / notes |
|-----------|-----------------|
| PHP | 8.2+ (8.3 recommended; 8.5 supported with project `database.php` SSL helper) |
| Composer | 2.x |
| MySQL | 8.0+ (shared DB for LAN deployments) |
| Extensions | `pdo_mysql`, `mbstring`, `openssl`, `tokenizer`, `xml`, `ctype`, `json`, `fileinfo`, `gd` or `imagick` as needed for exports |

### AI module only

| Component | Version / notes |
|-----------|-----------------|
| Python | 3.10+ |
| Ollama | Latest; models `nomic-embed-text`, `qwen2.5:7b-instruct` (or equivalent) |
| RAM | 16 GB minimum for 7B model on same host; 32 GB recommended for production + MySQL |
| Disk | Space for Chroma index under `ai_service/data/` (created at runtime, not in git) |

### Production / LAN server (5–6 clients, one host)

- **Windows Server** or **Linux** with static LAN IP
- Web server: **IIS + PHP**, **Apache + mod_php/FPM**, or **Nginx + PHP-FPM** (document root = `html/public`)
- MySQL on same machine or reachable host; firewall allows LAN clients to HTTP/HTTPS only
- Run **Ollama**, **uvicorn** (AI service), and optionally **`php artisan queue:work`** as Windows services or systemd units
- Set `APP_ENV=production`, `APP_DEBUG=false`, strong `APP_KEY`, HTTPS termination at reverse proxy
- Do **not** expose Ollama (11434) or AI service (8001) to the LAN unless intentionally secured

Detailed AI ops: [`html/docs/ai/windows-single-server-runbook.md`](html/docs/ai/windows-single-server-runbook.md)

---

## Quick start — macOS (development)

### 1. Install prerequisites

```bash
brew install php@8.3 composer mysql@8.0
brew services start mysql@8.0
```

Install [Ollama](https://ollama.com) for AI features.

### 2. Database

```bash
mysql -u root -e "CREATE DATABASE ledgersinfo_html CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
mysql -u root ledgersinfo_html < ledgersinfo_html.sql
```

**Security:** The SQL dump may contain sample users and data. Reset all passwords and review users before any real deployment.

### 3. Laravel

```bash
cd html
cp .env.example .env
# Edit DB_USERNAME / DB_PASSWORD
composer install --no-dev   # use without --no-dev for local dev
php artisan key:generate
php artisan storage:link
php artisan migrate --path=database/migrations/2026_05_28_200000_add_legacy_columns_to_users_table.php
php artisan migrate --path=database/migrations/2026_05_28_100000_create_ai_documents_table.php
php artisan migrate --path=database/migrations/2026_05_28_100100_create_ai_chat_sessions_table.php
php artisan migrate --path=database/migrations/2026_05_28_100200_create_ai_chat_messages_table.php
php artisan migrate --path=database/migrations/2026_05_28_100300_create_ai_surveys_table.php
php artisan migrate --path=database/migrations/2026_05_28_100400_create_ai_survey_questions_table.php
php artisan migrate --path=database/migrations/2026_05_28_100500_create_ai_survey_responses_table.php
php artisan db:seed --class=AiPermissionSeeder
```

> **Note:** A full `php artisan migrate` may fail on older legacy migrations if tables from the SQL import already exist. Prefer SQL import + targeted AI/legacy migrations above.

### 4. Run the app (25 MB uploads)

Use `serve-dev.sh` — not plain `php artisan serve` (upload limits):

```bash
chmod +x serve-dev.sh
./serve-dev.sh
```

Open http://127.0.0.1:8000 — login uses **username (`name`)** and password, not email.

### 5. AI service (separate terminal)

```bash
ollama pull nomic-embed-text
ollama pull qwen2.5:7b-instruct
cd ../ai_service
python3 -m venv .venv && source .venv/bin/activate
pip install -r requirements.txt
uvicorn main:app --host 127.0.0.1 --port 8001
```

If `AI_INGEST_SYNC=false` in `.env`, run `php artisan queue:work` in another terminal.

---

## Quick start — Windows (development)

### 1. Install

- [PHP 8.2+](https://windows.php.net/download/) (add to PATH, enable extensions in `php.ini`)
- [Composer](https://getcomposer.org/)
- [MySQL 8](https://dev.mysql.com/downloads/installer/)
- [Python 3.10+](https://www.python.org/downloads/)
- [Ollama for Windows](https://ollama.com)

Optional: set `MYSQLDUMP_PATH` in `.env` if backup cannot find `mysqldump.exe`.

### 2. Database

Import `ledgersinfo_html.sql` using MySQL Workbench or:

```cmd
mysql -u root -p -e "CREATE DATABASE ledgersinfo_html CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
mysql -u root -p ledgersinfo_html < ledgersinfo_html.sql
```

### 3. Laravel

```cmd
cd html
copy .env.example .env
composer install
php artisan key:generate
php artisan storage:link
```

Run the same targeted `migrate` / `db:seed` commands as in the macOS section (Git Bash or PowerShell).

### 4. Run Laravel

Development with upload limits — from `html\public`:

```cmd
php -d upload_max_filesize=25M -d post_max_size=30M -S 127.0.0.1:8000 ..\vendor\laravel\framework\src\Illuminate\Foundation\resources\server.php
```

Or use IIS/Apache pointing to `html\public` for production-like testing.

### 5. AI service

```cmd
ollama pull nomic-embed-text
ollama pull qwen2.5:7b-instruct
cd ai_service
python -m venv .venv
.venv\Scripts\activate
pip install -r requirements.txt
uvicorn main:app --host 127.0.0.1 --port 8001
```

---

## Production deployment checklist

1. Clone repo; `composer install --no-dev --optimize-autoloader` in `html/`
2. Copy `.env.example` → `.env`; set `APP_ENV=production`, `APP_DEBUG=false`, `APP_URL` to your LAN URL
3. `php artisan key:generate` (once)
4. Import or migrate database; run `AiPermissionSeeder`; assign roles via admin UI
5. Web server document root → `html/public`; PHP `upload_max_filesize` ≥ 25M for AI PDFs
6. `php artisan config:cache` `route:cache` `view:cache` (after `.env` is final)
7. Schedule/cron: `php artisan schedule:run` if you add scheduled tasks
8. **AI:** Ollama on `127.0.0.1:11434`, uvicorn on `127.0.0.1:8001`, `AI_SERVICE_URL` matching
9. Harden: HTTPS, firewall, separate DB user with least privilege, rotate default passwords
10. Backups: use in-app DB Backup or OS-level MySQL dumps to a secure path

### Suggested production hardware (single server, AI enabled)

| Resource | Guidance |
|----------|----------|
| CPU | 4+ cores |
| RAM | 32 GB (MySQL + Ollama 7B + PHP) |
| Disk | SSD 256 GB+ |
| Network | Gigabit LAN; static IP for the server |

---

## Environment variables

| Variable | Purpose |
|----------|---------|
| `DB_*` | MySQL connection |
| `AI_ENABLED` | Toggle AI module |
| `AI_SERVICE_URL` | FastAPI base URL (default `http://127.0.0.1:8001`) |
| `AI_INGEST_SYNC` | `true` = ingest in request; `false` = use queue worker |
| `MYSQLDUMP_PATH` | Optional override for database backups |

See [`html/.env.example`](html/.env.example).

---

## Permissions (AI module)

After seeding, grant roles these permissions (or use admin user):

- `ai.documents.manage`
- `ai.ask`
- `ai.surveys.manage`
- `ai.feedback.view`

Run: `php artisan db:seed --class=AiPermissionSeeder`

---

## Scripts

| Script | Purpose |
|--------|---------|
| [`scripts/safe-reimport-keep-ai.sh`](scripts/safe-reimport-keep-ai.sh) | Re-import SQL while preserving AI tables and uploaded documents |
| [`html/serve-dev.sh`](html/serve-dev.sh) | macOS/Linux dev server with 25 MB upload limits |

---

## Tests

```bash
cd html
php artisan test --filter=Ai
```

---

## Repository layout (what is not in git)

- `html/vendor/` — run `composer install`
- `html/.env` — secrets; use `.env.example`
- `ai_service/data/` — Chroma vector store (runtime)
- `backups/` — local DB backup exports
- `*.docx` — local hardware notes

---

## License

Proprietary / internal use unless otherwise specified by your organization.

## Author

[Himanshu Phulara](https://github.com/himanshuphulara)
