#!/bin/sh
set -e
cd /var/www/html

[ -f .env ] || cp .env.example .env

# Keep APP_KEY stable across rebuilds (otherwise every rebuild logs everyone out).
# Use $APP_KEY if provided, else a key persisted in the storage volume.
KEY_FILE=storage/app/.app_key
if [ -z "$APP_KEY" ]; then
  [ -s "$KEY_FILE" ] || php artisan key:generate --show > "$KEY_FILE"
  APP_KEY=$(cat "$KEY_FILE")
fi
sed -i "s|^APP_KEY=.*|APP_KEY=${APP_KEY}|" .env

echo "Waiting for database at ${DB_HOST}:${DB_PORT}..."
until php -r 'try { new PDO("mysql:host=".getenv("DB_HOST").";port=".getenv("DB_PORT"), getenv("DB_USERNAME"), getenv("DB_PASSWORD")); } catch (Exception $e) { exit(1); }' 2>/dev/null; do
  sleep 2
done

php artisan migrate --force
# The administrator and developer accounts (ADMIN_* / DEVELOPER_* env vars); safe to repeat.
php artisan db:seed --class=AdminAccountsSeeder --force
php artisan storage:link 2>/dev/null || true
php artisan config:clear
chown -R www-data:www-data storage bootstrap/cache files livewire-tmp .env

exec "$@"
