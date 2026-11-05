# DealBD — Docker + MySQL + phpMyAdmin (Windows PowerShell)

**Only Docker Desktop is needed on the host.** This package is a Laravel 13 *overlay*; the `bootstrap` service downloads and installs the missing framework files through Composer **inside Docker**. Internet access is required on the first run.

## 1. Configure the database

Open PowerShell in the extracted `dealbd-starter` directory:

```powershell
Copy-Item .env.docker.example .env
notepad .env
```

Change `DB_PASSWORD` and `MYSQL_ROOT_PASSWORD` to **different strong values**. Save the file. Leave `DB_HOST=db` and `DB_PORT=3306` (these are Docker-network settings).

## 2. Install Laravel inside Docker

```powershell
docker compose build app
docker compose run --rm bootstrap
```

The one-time bootstrap downloads the Laravel framework files, installs Composer packages and generates an application key. It does not overwrite your existing DealBD controllers/views. **Do not run the old `install.ps1` for this Docker-only method.**

## 3. Start services and create the schema

```powershell
docker compose up -d
docker compose exec app php artisan migrate --seed --force
docker compose --profile jobs up -d
docker compose ps
```

The `migrate --seed` command creates all required tables *in the MySQL database* and inserts categories and store examples. There is no SQL import step; **Laravel migrations are the source of truth**.

## 4. Open the website and phpMyAdmin

| Service | URL / connection |
|---|---|
| Website | http://localhost:8080 |
| phpMyAdmin | http://localhost:8081 |
| MySQL from other host desktop tools | `127.0.0.1:3307` |
| MySQL from Docker services | `db:3306` |

To log in to phpMyAdmin:
- **Username:** `dealbd` (or `DB_USERNAME` from `.env`)
- **Password:** the exact value you assigned to `DB_PASSWORD`
- **Server:** preconfigured as `db`; you normally won't enter it.

Select the `dealbd` database in phpMyAdmin to view or edit tables. The database is created automatically by MySQL on the first startup; migrations create the tables.

Register a normal user via http://localhost:8080/register.

### Optional first admin account

Add to `.env` temporarily:

```env
SEED_ADMIN_EMAIL=your-own-email@example.com
SEED_ADMIN_PASSWORD=your-own-random-password-of-at-least-12-characters
```

Then run:

```powershell
docker compose exec app php artisan db:seed --force
```

Remove the temporary `SEED_ADMIN_EMAIL` and `SEED_ADMIN_PASSWORD` lines after creating the admin. The seeder promotes the specified account to admin. Do not reuse admin credentials on production.

## Important commands

```powershell
# Inspect running containers
docker compose ps

# Logs (app, Nginx, database and phpMyAdmin)
docker compose logs -f app web db phpmyadmin

# Execute Artisan in the app container
docker compose exec app php artisan route:list

docker compose exec app php artisan migrate:status

# Stop containers, keeping database data
docker compose --profile jobs down

# Restart after code changes
docker compose --profile jobs up -d

# Optional automated feature tests (requires additional test configuration)
docker compose exec app php artisan test --filter=DealFlowTest
```

**Data protection:** Avoid `docker compose down -v` unless you deliberately want to delete all MySQL data in the named volume. Keep `.env` out of version control. phpMyAdmin is bound to localhost for development and must not be publicly exposed in production.

## Common fixes

- **Port already in use:** Change the host side of `8080:80`, `8081:80`, or `3307:3306` in `docker-compose.yml`. Use `127.0.0.1:<your-new-port>:<container-port>`; also update `APP_URL` if changing the site port.
- **Connection refused:** Check `docker compose ps`, `docker compose logs db` and `docker compose exec app php artisan migrate:status`. Inside Docker `DB_HOST` must be `db`, **not** `localhost`.
- **Can't log into phpMyAdmin after password change:** MySQL only initializes users/passwords when its data volume is created. Changing `.env` later does **not** automatically change credentials in an existing MySQL volume. Update the MySQL account password using SQL if you need to rotate it; don't delete your data volume to fix credentials.
- **Laravel 500 error:** Run `docker compose logs app web` and `docker compose exec app php artisan optimize:clear`; ensure migration completed, key generated, and `storage/` and `bootstrap/cache` are writable.
- **Composer download errors:** Docker needs working Internet/DNS access to download Laravel packages on the first bootstrap.

## Production note

This is a **local development** Compose setup. It uses `APP_DEBUG=true` and exposes phpMyAdmin to loopback. Before production, disable debug mode, configure HTTPS, external backups, worker monitoring, secure secret management, and limit administrative access. The starter still needs full integration testing and a security review.
