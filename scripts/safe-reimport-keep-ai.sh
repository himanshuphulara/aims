#!/usr/bin/env bash
#
# Safe re-import of ledgersinfo_html.sql while keeping AI documents + PDF files.
# Run from repo root: ./scripts/safe-reimport-keep-ai.sh
#
set -euo pipefail

ROOT="$(cd "$(dirname "$0")/.." && pwd)"
SQL_FILE="${ROOT}/ledgersinfo_html.sql"
HTML="${ROOT}/html"
DB="ledgersinfo_html"
MYSQL_USER="${MYSQL_USER:-root}"
BACKUP_DIR="${ROOT}/backups/reimport-$(date +%Y%m%d-%H%M%S)"
ADMIN_PASSWORD="${ADMIN_PASSWORD:-Admin@123}"

if [[ ! -f "$SQL_FILE" ]]; then
  echo "Missing SQL dump: $SQL_FILE"
  exit 1
fi

echo "=== AIMS safe re-import ==="
echo "Database: $DB"
echo "Backup folder: $BACKUP_DIR"
echo ""
echo "This will DROP and recreate '$DB', import the SQL dump, re-add AI tables,"
echo "restore your AI uploads, and reset password for user 'admin' to: $ADMIN_PASSWORD"
echo ""
read -r -p "Continue? (type yes): " CONFIRM
if [[ "$CONFIRM" != "yes" ]]; then
  echo "Aborted."
  exit 0
fi

mkdir -p "$BACKUP_DIR"

echo ""
echo "[1/8] Backing up AI tables..."
mysqldump -u "$MYSQL_USER" "$DB" \
  --no-create-info \
  --skip-triggers \
  --set-gtid-purged=OFF \
  ai_documents \
  ai_chat_sessions \
  ai_chat_messages \
  ai_surveys \
  ai_survey_questions \
  ai_survey_responses \
  > "$BACKUP_DIR/ai_tables_data.sql" 2>/dev/null || true

if [[ ! -s "$BACKUP_DIR/ai_tables_data.sql" ]]; then
  echo "  (no AI rows to backup — skipping data restore later)"
  touch "$BACKUP_DIR/ai_tables_data.sql"
fi

echo "[2/8] Backing up uploaded PDFs..."
if [[ -d "$HTML/storage/app/ai/documents" ]]; then
  cp -a "$HTML/storage/app/ai" "$BACKUP_DIR/ai_storage"
else
  mkdir -p "$BACKUP_DIR/ai_storage/documents"
  echo "  (no PDF folder found)"
fi

OLD_ADMIN_ID="$(mysql -u "$MYSQL_USER" -N -e "SELECT id FROM ${DB}.users WHERE name='admin' ORDER BY id LIMIT 1;" 2>/dev/null || echo "1")"
echo "$OLD_ADMIN_ID" > "$BACKUP_DIR/old_admin_id.txt"
echo "  Current admin user id: $OLD_ADMIN_ID"

echo "[3/8] Dropping and recreating database..."
mysql -u "$MYSQL_USER" -e "DROP DATABASE IF EXISTS \`${DB}\`; CREATE DATABASE \`${DB}\` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"

echo "[4/8] Importing ledgersinfo_html.sql (may take a minute)..."
mysql -u "$MYSQL_USER" "$DB" < "$SQL_FILE"

echo "[5/8] Running AI table migrations only..."
cd "$HTML"
for path in \
  database/migrations/2026_05_28_100000_create_ai_documents_table.php \
  database/migrations/2026_05_28_100100_create_ai_chat_sessions_table.php \
  database/migrations/2026_05_28_100200_create_ai_chat_messages_table.php \
  database/migrations/2026_05_28_100300_create_ai_surveys_table.php \
  database/migrations/2026_05_28_100400_create_ai_survey_questions_table.php \
  database/migrations/2026_05_28_100500_create_ai_survey_responses_table.php
do
  php artisan migrate --path="$path" --force 2>&1 | grep -v Deprecated || true
done

echo "[6/8] Seeding AI permissions..."
php artisan db:seed --class=AiPermissionSeeder --force 2>&1 | grep -v Deprecated || true

echo "[7/8] Restoring AI data and PDF files..."
NEW_ADMIN_ID="$(mysql -u "$MYSQL_USER" -N -e "SELECT id FROM ${DB}.users WHERE name='admin' ORDER BY id LIMIT 1;")"
echo "  Dump admin user id: $NEW_ADMIN_ID"

if [[ -s "$BACKUP_DIR/ai_tables_data.sql" ]] && grep -q "INSERT INTO" "$BACKUP_DIR/ai_tables_data.sql"; then
  mysql -u "$MYSQL_USER" "$DB" -e "SET FOREIGN_KEY_CHECKS=0;"
  sed '/GTID_PURGED/d;/MYSQLDUMP_TEMP_LOG_BIN/d;/@@SESSION.SQL_LOG_BIN/d' \
    "$BACKUP_DIR/ai_tables_data.sql" | mysql -u "$MYSQL_USER" "$DB"
  mysql -u "$MYSQL_USER" "$DB" -e "
    SET FOREIGN_KEY_CHECKS=0;
    UPDATE ai_documents SET user_id = ${NEW_ADMIN_ID} WHERE user_id = ${OLD_ADMIN_ID} OR user_id IS NULL;
    UPDATE ai_chat_sessions SET user_id = ${NEW_ADMIN_ID} WHERE user_id = ${OLD_ADMIN_ID} OR user_id IS NULL;
    UPDATE ai_chat_messages SET user_id = ${NEW_ADMIN_ID} WHERE user_id = ${OLD_ADMIN_ID} OR user_id IS NULL;
    UPDATE ai_surveys SET user_id = ${NEW_ADMIN_ID} WHERE user_id = ${OLD_ADMIN_ID} OR user_id IS NULL;
    SET FOREIGN_KEY_CHECKS=1;
  "
fi

mkdir -p "$HTML/storage/app/ai"
if [[ -d "$BACKUP_DIR/ai_storage/documents" ]]; then
  cp -a "$BACKUP_DIR/ai_storage/." "$HTML/storage/app/ai/"
fi

echo "[8/8] Resetting local admin password and AI permissions..."
php artisan tinker --execute="
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
\$perms = ['ai.documents.manage','ai.ask','ai.surveys.manage','ai.feedback.view'];
\$u = User::where('name','admin')->first();
if (\$u) {
  \$u->password = Hash::make('${ADMIN_PASSWORD}');
  \$u->role = '1';
  \$u->save();
  \$u->syncPermissions(\$perms);
  echo 'Admin id='.\$u->id.' password reset, role=1, AI perms synced\n';
}
foreach (Role::all() as \$role) {
  try { \$role->givePermissionTo(\$perms); } catch (\Throwable \$e) {}
}
" 2>&1 | grep -v Deprecated || true

php artisan permission:cache-reset 2>&1 | grep -v Deprecated || true

echo ""
echo "=== Done ==="
echo "Backup saved at: $BACKUP_DIR"
echo "Login: name=admin  password=${ADMIN_PASSWORD}"
echo ""
mysql -u "$MYSQL_USER" "$DB" -e "
  SELECT 'users' t, COUNT(*) c FROM users
  UNION SELECT 'categories', COUNT(*) FROM categories
  UNION SELECT 'vouchers', COUNT(*) FROM vouchers
  UNION SELECT 'ai_documents', COUNT(*) FROM ai_documents;
"
