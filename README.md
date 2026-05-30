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

### 1. Install Prerequisites (Beginner's Guide)

If you do not have the required software installed, follow these steps to install and configure them:

* **Python 3.11 (⚠️ Crucial Version):**
  1. Download the installer from the [Python 3.11.9 Release Page](https://www.python.org/downloads/release/python-3119/) (select **Windows installer (64-bit)**).
  2. Run the installer.
  3. **IMPORTANT:** Check the box at the bottom that says **"Add python.exe to PATH"** before clicking "Install Now".

* **PHP 8.2 or 8.3:**
  1. Download the **Thread Safe** Zip file from the [PHP for Windows Download Page](https://windows.php.net/download/).
  2. Extract the downloaded zip file into a new folder named `C:\php` (so the path is `C:\php\php.exe`).
  3. Open your Windows Start Menu, search for **"Edit the system environment variables"**, click it, then click **Environment Variables**.
  4. Under "System variables", double-click **Path**, click **New**, and paste `C:\php`. Click **OK** to save.
  5. In `C:\php`, make a copy of `php.ini-development` and rename it to `php.ini`.
  6. Open `php.ini` in a text editor (like Notepad), search for the following lines, and remove the starting semicolon (`;`) to enable them:
     ```ini
     extension_dir = "ext"
     extension=fileinfo
     extension=gd
     extension=mbstring
     extension=openssl
     extension=pdo_mysql
     ```

* **Composer:**
  1. Download the installer from the [Composer Download Page](https://getcomposer.org/download/) (click on **Composer-Setup.exe**).
  2. Run the installer and choose "Install for all users".
  3. Point the installer to your PHP executable at `C:\php\php.exe` when prompted, and complete the setup.

* **MySQL 8.0+:**
  1. Download the installer from [MySQL Community Downloads](https://dev.mysql.com/downloads/installer/).
  2. Select the **MySQL Installer Web Community** option and run it.
  3. Choose "Developer Default" or "Server Only" setup, set a password for the `root` user when prompted, and complete the installation.

* **Ollama for Windows:**
  1. Download the installer from the [Ollama Download Page](https://ollama.com/download/windows).
  2. Run the installer. It will run in your background system tray automatically.


### 2. Initialize and Start MySQL
If you installed MySQL manually or the service is not running, open **PowerShell as Administrator** and run:
```powershell
# 1. Initialize the data directory (insecure: blank root password)
& "C:\Program Files\MySQL\MySQL Server 8.4\bin\mysqld.exe" --initialize-insecure

# 2. Install MySQL as a Windows Service
& "C:\Program Files\MySQL\MySQL Server 8.4\bin\mysqld.exe" --install

# 3. Start the service
Start-Service MySQL
```

### 3. Database Import
Import the base schema and sample data:
```cmd
# Create the database
"C:\Program Files\MySQL\MySQL Server 8.4\bin\mysql.exe" -u root -e "CREATE DATABASE ledgersinfo_html CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"

# Import sql dump
"C:\Program Files\MySQL\MySQL Server 8.4\bin\mysql.exe" -u root ledgersinfo_html < ledgersinfo_html.sql
```

### 4. Laravel Application Configuration

You can automate the Laravel setup (copying `.env`, running composer install, generating keys, and linking storage) on Windows. Navigate to the `html` folder and run:
```cmd
setup_laravel_windows.bat
```

Alternatively, to perform setup manually:
1. Navigate to the `html` folder:
   ```cmd
   cd html
   ```
2. Copy the environment file:
   ```cmd
   copy .env.example .env
   ```
3. Open `.env` and verify the database configuration:
   - `DB_DATABASE=ledgersinfo_html`
   - `DB_USERNAME=root`
   - `DB_PASSWORD=` (blank if initialized with insecure)
   - Add `MYSQLDUMP_PATH` using **forward slashes** (to avoid escape issues in PHP Dotenv):
     ```env
     MYSQLDUMP_PATH="C:/Program Files/MySQL/MySQL Server 8.4/bin/mysqldump.exe"
     ```
   - Update `AI_REQUEST_TIMEOUT` to prevent timeout on model cold-starts:
     ```env
     AI_REQUEST_TIMEOUT=180
     ```

4. Install Composer dependencies:
   ```cmd
   composer install
   ```

5. Create missing caches and directories if they do not exist:
   ```powershell
   # If bootstrap/cache or storage directories are missing
   mkdir bootstrap/cache
   mkdir storage/framework/cache/data
   mkdir storage/framework/sessions
   mkdir storage/framework/views
   mkdir storage/logs
   ```

6. Generate the app key and link the storage:
   ```cmd
   php artisan key:generate
   php artisan storage:link
   ```

7. Run the targeted AI migrations & seed the permissions:
   ```cmd
   php artisan migrate --path=database/migrations/2026_05_28_200000_add_legacy_columns_to_users_table.php --force
   php artisan migrate --path=database/migrations/2026_05_28_100000_create_ai_documents_table.php --force
   php artisan migrate --path=database/migrations/2026_05_28_100100_create_ai_chat_sessions_table.php --force
   php artisan migrate --path=database/migrations/2026_05_28_100200_create_ai_chat_messages_table.php --force
   php artisan migrate --path=database/migrations/2026_05_28_100300_create_ai_surveys_table.php --force
   php artisan migrate --path=database/migrations/2026_05_28_100400_create_ai_survey_questions_table.php --force
   php artisan migrate --path=database/migrations/2026_05_28_100500_create_ai_survey_responses_table.php --force
   php artisan db:seed --class=AiPermissionSeeder --force
   ```

### 5. Run the Laravel Web Server
From the `html/public` folder, launch the server. Ensure you increase the PHP execution time to handle Ollama model loading times:
```cmd
cd public
php -d upload_max_filesize=25M -d post_max_size=30M -d max_execution_time=300 -S 127.0.0.1:8000 ..\vendor\laravel\framework\src\Illuminate\Foundation\resources\server.php
```
Open **[http://127.0.0.1:8000](http://127.0.0.1:8000)** in your browser.
- **Default login**: Username: `admin` / Password: `Admin@123`

### 6. AI FastAPI Service Setup
1. Pull the Ollama models (run from command line):
   ```cmd
   ollama pull nomic-embed-text
   ollama pull qwen2.5:7b-instruct
   ```
2. Set up the virtual environment using **Python 3.11** in the `ai_service` folder:
   ```cmd
   cd ../ai_service
   python -m venv .venv
   .venv\Scripts\activate
   pip install -r requirements.txt
   ```
3. Run the FastAPI service:
   ```cmd
   uvicorn main:app --host 127.0.0.1 --port 8001
   ```

### Subsequent Runs (Starting & Stopping Services)

If you have already performed the initial setup and installed all dependencies, you can start or stop both the Laravel web server and the AI FastAPI service easily on Windows:

* **To Start:**
  Open a Command Prompt in the **root** of the repository and run:
  ```cmd
  start_aims_windows.bat
  ```
  *(This script will perform health diagnostics, automatically launch MySQL/Ollama if they are offline, run missing setups, and start both services in separate console windows).*

* **To Stop:**
  Run the cleanup script from the **root** of the repository:
  ```cmd
  stop_aims_windows.bat
  ```
  *(This will find and terminate the processes running on ports 8000 and 8001, and attempt to stop the MySQL service).*

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


