#!/bin/sh
set -e

# Pastikan storage & cache writable (bind mount sering root-owned)
chown -R www-data:www-data storage bootstrap/cache 2>/dev/null || true
chmod -R 775 storage bootstrap/cache 2>/dev/null || true

# Jika APP_KEY kosong, generate sementara biar `compose up` langsung jalan
# Di produksi, isi APP_KEY di .env server agar persist
if [ -z "$APP_KEY" ] || [ "$APP_KEY" = "" ]; then
  echo "[entrypoint] APP_KEY kosong, generate sementara..."
  export APP_KEY=$(php artisan key:generate --show 2>/dev/null || echo "")
  if [ -n "$APP_KEY" ]; then
    echo "[entrypoint] APP_KEY generated: $APP_KEY"
    echo "[entrypoint] WARNING: Set APP_KEY ini ke .env host agar persist: APP_KEY=$APP_KEY"
  fi
fi

# Tunggu DB siap (maks 30 detik)
echo "[entrypoint] Menunggu database ($DB_HOST:$DB_PORT/$DB_DATABASE)..."
for i in $(seq 1 15); do
  if php artisan migrate:status >/dev/null 2>&1; then
    echo "[entrypoint] Database siap."
    break
  fi
  echo "[entrypoint] DB belum siap, retry $i/15..."
  sleep 2
done

# Auto migrate jika AUTO_MIGRATE=true atau APP_ENV=local
if [ "$AUTO_MIGRATE" = "true" ] || [ "$APP_ENV" = "local" ]; then
  echo "[entrypoint] Menjalankan migrate..."
  php artisan migrate --force 2>&1 | tail -n 20 || true
fi

# Cache config untuk production
if [ "$APP_ENV" = "production" ]; then
  php artisan config:cache 2>/dev/null || true
  php artisan route:cache 2>/dev/null || true
fi

exec "$@"
