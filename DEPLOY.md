# Deploy ke Ubuntu Server (Docker)

Panduan cepat untuk menjalankan `ticket-it-app` di VPS Ubuntu 22.04/24.04 dengan PostgreSQL di Docker.

## Arsitektur
- `app` — `php:8.5-fpm` (Laravel) + `scheduler` (`php artisan schedule:work` untuk `tickets:check-sla`)
- `db` — `postgres:18-alpine` volume `pgdata` (`5433:5432` dev, tidak di-expose di prod)
- `adminer` — DB admin di `8081:8080` (`ADMINER_DEFAULT_SERVER=db`)
- `nginx` — reverse ke `app:9000`, serve `public/` (`8001:80` dev, `80:80` prod)
- Env tidak di-bake ke image; di-injeksi via `environment` di `docker-compose.yml` dari `.env` server.
- `docker/entrypoint.sh` auto-generate `APP_KEY` jika kosong & auto `migrate` (`AUTO_MIGRATE=true` dev), jadi `docker compose up --build` langsung jalan.
- `docker-compose.override.yml` (dev) bind mount `.:/var/www/html` + volume `vendor`/`public_build` biar edit host langsung reflektif tanpa `composer install` di host.

## 1) Siapkan Server
```bash
sudo apt update && sudo apt install -y git curl ufw
# Docker + Compose plugin
curl -fsSL https://get.docker.com | sh
sudo usermod -aG docker $USER
# logout & login lagi
docker --version
docker compose version
# Firewall: buka 22 (SSH) + 80 (HTTP) + 443 (HTTPS jika pakai SSL)
sudo ufw allow OpenSSH
sudo ufw allow 80/tcp
sudo ufw allow 443/tcp
sudo ufw enable
```

## 2) Clone & Buat .env Produksi
```bash
git clone https://github.com/erlanggandi/support-desk.git
cd support-desk
cp .env.example .env
nano .env
```

Isi minimal (`nano .env`):
```ini
APP_NAME="Support Desk"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://support.domain-anda.com   # ganti IP/domain VPS
APP_TIMEZONE=Asia/Singapore
LOG_CHANNEL=stack
LOG_LEVEL=warning

# DB — DB_HOST harus `db` (nama service), bukan 127.0.0.1
DB_CONNECTION=pgsql
DB_HOST=db
DB_PORT=5432
DB_DATABASE=ticket_it_app
DB_USERNAME=postgres
DB_PASSWORD=GANTI_PASSWORD_KUAT_32_CHAR

# Dipakai oleh service `db` (harus sama dengan DB_* di atas)
POSTGRES_DB=ticket_it_app
POSTGRES_USER=postgres
POSTGRES_PASSWORD=GANTI_PASSWORD_KUAT_32_CHAR
POSTGRES_HOST_PORT=127.0.0.1:5433  # dev saja; di prod override jadi kosong

NGINX_HOST_PORT=80
```

Generate `APP_KEY` — lakukan **setelah build** (butuh `vendor` di dalam image, belum ada di host fresh clone):
```bash
# 1) Build dulu (isi APP_KEY kosong tidak masalah untuk build)
docker compose -f docker-compose.yml -f docker-compose.prod.yml up -d --build
# 2) Generate key via container yang sudah punya vendor
docker compose -f docker-compose.yml -f docker-compose.prod.yml exec app php artisan key:generate --show
# copy output `base64:...` → paste ke APP_KEY di .env di host
nano .env  # APP_KEY=base64:...
# 3) Restart agar app baca APP_KEY baru
docker compose -f docker-compose.yml -f docker-compose.prod.yml up -d
docker compose -f docker-compose.yml -f docker-compose.prod.yml exec app php artisan config:cache
```
Alternatif tanpa Docker (butuh `openssl` di host):
```bash
echo "base64:$(openssl rand -base64 32)"
# paste ke APP_KEY
```

> **PENTING:** Repo pernah bocorkan `.env` di commit `21c9afa`. Jangan reuse `APP_KEY` lama. Generate baru di server.

## 3) Build & Jalankan

**Cukup `up` saja (auto APP_KEY + migrate):**
```bash
docker compose up -d --build
docker compose ps                    # tunggu db (healthy) & app ready
curl -I http://localhost:8001/       # 200
# Adminer: http://localhost:8081  (System: PostgreSQL, Server: db, User: postgres, Password: postgres, DB: ticket_it_app)
```

Dev / staging manual (jika `AUTO_MIGRATE=false`):
```bash
docker compose exec app php artisan migrate --force
docker compose exec app php artisan db:seed --force   # sekali saja
docker compose exec app php artisan config:cache
```

Production (DB tidak di-expose, prod env):
```bash
docker compose -f docker-compose.yml -f docker-compose.prod.yml up -d --build
docker compose -f docker-compose.yml -f docker-compose.prod.yml exec app php artisan migrate --force
docker compose -f docker-compose.yml -f docker-compose.prod.yml exec app php artisan config:cache
```

Buka `http://IP_VPS/` atau `https://domain-anda.com` → login `admin@helpdesk.local` / `password` (ganti setelah login).

## 4) Update Aplikasi
```bash
git pull origin main
docker compose -f docker-compose.yml -f docker-compose.prod.yml up -d --build
docker compose exec app php artisan migrate --force
docker compose exec app php artisan config:cache
```

## 5) Operasional
```bash
docker compose logs -f app
docker compose logs -f scheduler
docker compose logs -f nginx
docker compose exec app php artisan tinker
docker compose exec db psql -U postgres -d ticket_it_app -c "SELECT count(*) FROM tickets;"
docker compose down          # stop
docker compose down -v       # stop + hapus volume pgdata (hati-hati!)
```

Backup volume PostgreSQL:
```bash
docker run --rm -v ticket-it-app_pgdata:/data -v $(pwd):/backup alpine tar czf /backup/pgdata-$(date +%F).tar.gz /data
```

Restore:
```bash
docker compose down
docker volume rm ticket-it-app_pgdata
docker volume create ticket-it-app_pgdata
docker run --rm -v ticket-it-app_pgdata:/data -v $(pwd):/backup alpine sh -c "tar xzf /backup/pgdata-YYYY-MM-DD.tar.gz -C /"
docker compose up -d
```

## 6) SSL (Opsional)
Opsi A — Certbot di host Nginx (klasik). Opsi B — pasang Caddy/Traefik di depan `nginx`. Contoh paling simpel: jalankan `certbot --nginx` di host setelah point domain ke IP VPS, lalu reverse proxy `80`/`443` ke `127.0.0.1:80`.

## Troubleshooting
- `pull access denied for ticket-it-app-app` → pastikan `docker-compose.yml` pakai `image: ticket-it-app:latest` + `build` (sudah fixed).
- `target scheduler: failed to solve: apt-get ... exit code: 2` → sudah fixed (hapus `-j$(nproc)` di Dockerfile 2026-08-28).
- `ports are not available: 8080/8000` → ganti `NGINX_HOST_PORT` di `.env` (dev pakai `8001`, prod pakai `80`).
- `SQLSTATE[08006] no password supplied` → cek `POSTGRES_PASSWORD` == `DB_PASSWORD` di `.env`.
- `APP_KEY not set` → generate ulang seperti langkah 2 (harus setelah `up --build`).
- `failed opening required '/app/vendor/autoload.php'` → terjadi jika `docker run -v $(pwd):/app php:8.5-cli php /app/artisan ...` di fresh clone (vendor belum ada). Ikuti urutan baru di langkah 2: build dulu baru `exec app php artisan key:generate --show`.
