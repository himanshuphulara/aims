#!/usr/bin/env bash
# Run PHP's built-in server from public/ (same as `artisan serve`) with 25 MB uploads.
# `artisan serve` spawns a child PHP that ignores -c php-dev.ini, so we call -S directly.
set -euo pipefail
ROOT="$(cd "$(dirname "$0")" && pwd)"
ROUTER="${ROOT}/vendor/laravel/framework/src/Illuminate/Foundation/resources/server.php"

cd "${ROOT}/public"

exec php \
  -d upload_max_filesize=25M \
  -d post_max_size=30M \
  -d max_execution_time=300 \
  -d memory_limit=512M \
  -S 127.0.0.1:8000 \
  "${ROUTER}"
