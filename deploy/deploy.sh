#!/usr/bin/env bash
set -euo pipefail

APP_DIR="${APP_DIR:-/var/www/rt-rw}"

require_command() {
	command -v "$1" >/dev/null 2>&1 || {
		echo "ERROR: command '$1' tidak ditemukan." >&2
		exit 1
	}
}

require_command php
require_command composer

if [ ! -d "$APP_DIR" ]; then
	echo "ERROR: APP_DIR tidak ditemukan: $APP_DIR" >&2
	exit 1
fi

cd "$APP_DIR"

if [ ! -f .env ]; then
	echo "ERROR: .env belum dibuat. Salin .env.vps.example lalu isi kredensial production." >&2
	exit 1
fi

if grep -q '^APP_KEY=\($\|base64:REPLACE_WITH_' .env; then
	echo "ERROR: APP_KEY belum diisi. Jalankan: php artisan key:generate --force" >&2
	exit 1
fi

if grep -q '^APP_DEBUG=true' .env; then
	echo "ERROR: APP_DEBUG harus false untuk production." >&2
	exit 1
fi

mkdir -p public/uploads storage/framework/{cache,sessions,views} storage/logs bootstrap/cache

composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader
php artisan optimize:clear --no-interaction

# Test koneksi sebelum migration agar kredensial DB salah tidak meninggalkan deploy setengah jadi.
php artisan migrate:status --no-interaction >/dev/null
php artisan migrate --force
php artisan storage:link --no-interaction || true
php artisan config:cache --no-interaction
php artisan route:cache --no-interaction
php artisan view:cache --no-interaction

chown -R www-data:www-data storage bootstrap/cache public/uploads
find storage bootstrap/cache -type d -exec chmod 775 {} \;
find storage bootstrap/cache -type f -exec chmod 664 {} \;

echo "RT/RW deployed successfully at $APP_DIR"