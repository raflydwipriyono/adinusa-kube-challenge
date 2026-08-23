# Backend Laravel
 
Laravel backend for the ecommerce app. Runs on PHP 8.3-FPM + Nginx +
Supervisor inside a single container (see `Dockerfile`).

## Local Deployment
#### Preparation

1. Clone Repo
```
git clone https://github.com/raflydwipriyono/challenge-adinusa.git
cd backend-laravel
```

2. Install dependencies
```
sudo add-apt-repository ppa:ondrej/php
sudo apt install php8.2 php8.2-cli php8.2-mbstring php8.2-xml php8.2-curl php8.2-bcmath php8.2-intl php8.2-mysql -y

apt install composer
```
```
composer install
```

3. Setup .env
```
cp .env.example .env

APP_NAME=Laravel
APP_ENV=local
APP_KEY=base64:KlIC99SqkK86/0vSumTTvLua/eiR0qfRjj34yZ2NM6k=
APP_DEBUG=true
APP_TIMEZONE=UTC
APP_URL=http://192.168.88.10:8000
FRONTEND_URL=http://192.168.88.10:3000


DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=laravel
DB_PASSWORD=secret
```

4. Run MySQL
- MySQL
```
docker compose -f docker-compose.db.yaml up -d
```

5. Generate key migrate
```
php artisan key:generate
php artisan migrate
```

6. Run Laravel
```
php artisan serve --host=0.0.0.0 --port=8000
```

App is now available at `http://192.168.88.10:8000`.

---
 
## Docker Image
 
The `Dockerfile` in this folder builds a self-contained image: PHP-FPM +
Nginx + Supervisor, serving on port `80`.
 
What happens at build time (already done inside the image, Kubernetes just runs the finished image, it doesn't redo these steps):
 
- `composer install --optimize-autoloader --no-interaction`
- Nginx config generated, pointing at `public/index.php`
- Supervisor config generated, running `php-fpm` and `nginx` together
- OPcache enabled and tuned
What is **not** baked into the image, and must be provided at container
runtime instead:
 
- `APP_KEY` — generate once (see below), never rebuild the image to change it
- Database credentials and host (`DB_*`)
- `APP_URL`, `FRONTEND_URL`, `APP_ENV`

### Generate `APP_KEY` for a built image
 
```bash
docker run --rm your-registry.com/ecommerce/backend-laravel:<tag> \
  php artisan key:generate --show
```
 
Prints one line like `base64:XxXxXxXx...=`. Save it — this is the value used
for `APP_KEY` wherever the image is deployed. Do not regenerate it after
data has been encrypted with it (sessions, secrets, etc. become unreadable).
 
### Run database migrations against a built image
 
```bash
docker run --rm \
  -e DB_CONNECTION=mysql \
  -e DB_HOST=<your-db-host> \
  -e DB_PORT=3306 \
  -e DB_DATABASE=<your-db-name> \
  -e DB_USERNAME=<your-db-user> \
  -e DB_PASSWORD=<your-db-password> \
  -e APP_KEY=<your-generated-key> \
  your-registry.com/ecommerce/backend-laravel:<tag> \
  php artisan migrate --force
```
 
`--force` is required because Laravel blocks migrations by default when
`APP_ENV` is not `local`.
 
---
 
## Environment Variables Reference
 
| Variable | Required | Notes |
|---|---|---|
| `APP_KEY` | Yes | Generate once with `key:generate --show`, never rotate casually |
| `APP_ENV` | Yes | `local`, `staging`, `production`, etc. |
| `APP_URL` | Yes | Public URL of this backend |
| `FRONTEND_URL` | Yes | Public URL of the frontend, used for CORS/redirects |
| `DB_CONNECTION` | Yes | `mysql` |
| `DB_HOST` | Yes | Database hostname or service name |
| `DB_PORT` | Yes | `3306` for MySQL |
| `DB_DATABASE` | Yes | Database name |
| `DB_USERNAME` | Yes | Database user |
| `DB_PASSWORD` | Yes | Database password |
 
---
 
## Notes
 
- The image serves on container port **80** (Nginx), not `8000` — `8000` is
  only used by `php artisan serve` in local dev, not inside the container.
- `.env` file in this repo is not required/used inside the built image for these variables — they
  are meant to be injected as environment variables at runtime (e.g.
  Kubernetes Secrets)
 