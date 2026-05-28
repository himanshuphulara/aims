# Safe database re-import (keep AI documents)

Use this when the app has **empty categories/vouchers** but you want production data from `ledgersinfo_html.sql`, without losing **AI Knowledge** uploads.

## What it does

1. Backs up all `ai_*` table rows and `storage/app/ai/` PDFs  
2. Drops and recreates database `ledgersinfo_html`  
3. Imports `ledgersinfo_html.sql` (full ledger data + `users.role` + permissions `type` column)  
4. Runs **only** the six AI Laravel migrations (not full `migrate`, which can fail on old migrations)  
5. Restores AI rows and PDF files (remaps `user_id` to dump `admin` id)  
6. Resets `admin` password to `Admin@123` (override with env `ADMIN_PASSWORD`)  
7. Seeds AI permissions  

## Before you start

- Stop heavy use of the app (optional).  
- Ensure MySQL is running.  
- Start the app with `./serve-dev.sh` after import.  
- AI service on port 8001 if you want to re-index or Ask AI immediately.  

## One-command run

From the repo root:

```bash
chmod +x scripts/safe-reimport-keep-ai.sh
./scripts/safe-reimport-keep-ai.sh
```

Type `yes` when prompted.

Custom admin password:

```bash
ADMIN_PASSWORD='YourSecurePass123!' ./scripts/safe-reimport-keep-ai.sh
```

## Manual steps (same as the script)

```bash
# 1. Backup AI
BACKUP=backups/manual-$(date +%Y%m%d)
mkdir -p "$BACKUP"
mysqldump -u root ledgersinfo_html --no-create-info \
  ai_documents ai_chat_sessions ai_chat_messages \
  ai_surveys ai_survey_questions ai_survey_responses \
  > "$BACKUP/ai_tables_data.sql"
cp -a html/storage/app/ai "$BACKUP/ai_storage"

# 2. Re-import dump
mysql -u root -e "DROP DATABASE IF EXISTS ledgersinfo_html; \
  CREATE DATABASE ledgersinfo_html CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
mysql -u root ledgersinfo_html < ledgersinfo_html.sql

# 3. AI migrations only
cd html
php artisan migrate --path=database/migrations/2026_05_28_100000_create_ai_documents_table.php --force
# ... repeat for 100100, 100200, 100300, 100400, 100500

# 4. Restore AI + fix user_id (see script)
# 5. php artisan db:seed --class=AiPermissionSeeder
```

## After import

| Item | Value |
|------|--------|
| Login field | **name** (username), not email |
| Default admin | `admin` / `Admin@123` (if you used the script) |
| Production users | Also in dump (ids 28, 29, 30) — passwords are production hashes |
| AI documents | Restored if backup existed; status `ready` should still work if PDF paths match |

## Verify

```bash
mysql -u root ledgersinfo_html -e "
  SELECT COUNT(*) categories FROM categories;
  SELECT COUNT(*) vouchers FROM vouchers;
  SELECT id,name,role FROM users WHERE name='admin';
  SELECT id,title,status FROM ai_documents;
"
```

Open **Funds** menu — parent categories should appear.  
Open **AI Knowledge → Documents** — your PDFs should list.

## If something fails

- **Import errors** in SQL: note the table name; the dump may reference a table your MySQL version rejects.  
- **AI PDFs missing**: copy from `backups/reimport-*/ai_storage/` into `html/storage/app/ai/`.  
- **Cannot log in**: reset password via tinker or re-run script step 8.  
- **No AI menu**: `php artisan db:seed --class=AiPermissionSeeder` and log out/in.  

## Do not run

- `php artisan migrate` (full) on a fresh dump — may error on `vouchers_bbf` alterations already in the dump.  
- `php artisan migrate:fresh` — wipes everything including AI.  
