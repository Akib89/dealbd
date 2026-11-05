# DealBD — Laravel 13 Community Deals Starter

An original Bangladesh-focused, community-driven deals platform starter inspired by the broad product patterns of Slickdeals and OzBargain. This is **not their source code, branding, or a complete feature-for-feature clone**.

## What's implemented

- Public homepage: hot / new / top deals, search and category/store/payment filtering
- Registration, login, logout, session management and CSRF protection
- Member deal creation and editing, merchant disclosure and coupon/payment conditions
- Staff moderation: approve/reject/feature/unfeature/expire
- Upvotes and downvotes (one vote per account; toggle to retract)
- Comments, saved deals, user reports and staff comment hiding
- Deal alerts with database notifications on newly approved matching offers
- User dashboard with submitted and saved deals
- Merchant dashboard (deal status and aggregate outbound clicks)
- Manually submitted price observations (NOT automatic merchant price monitoring)
- Scheduled expiry command, feature tests and responsive CSS

**Important:** This is a substantial *starter overlay*. It expects a fresh Laravel 13 installation. The archive intentionally does not contain Laravel framework/vendor dependencies, `.env`, secrets or generated keys. You need to install Laravel via Composer first.

## Requirements

- PHP >= 8.3
- Composer 2.x
- Laravel 13.x
- MySQL 8+ for production, or SQLite for local prototyping
- Docker + Docker Compose optional

## Docker-only quick start (recommended for Windows)

To run **Laravel + MySQL + phpMyAdmin** using Docker Desktop and *without installing PHP or Composer on your PC*, follow **[DOCKER_SETUP.md](DOCKER_SETUP.md)**. In short:

```powershell
Copy-Item .env.docker.example .env
notepad .env # set unique DB_PASSWORD and MYSQL_ROOT_PASSWORD
docker compose build app
docker compose run --rm bootstrap
docker compose up -d
docker compose exec app php artisan migrate --seed --force
docker compose --profile jobs up -d
```

Your website is at `http://localhost:8080`, phpMyAdmin at `http://localhost:8081` (log in with `DB_USERNAME` / `DB_PASSWORD`), and MySQL is `db:3306` inside Docker. The host port is `127.0.0.1:3307`.

## Automated installation

When Composer and PHP are installed, open a terminal **in the extracted `dealbd-starter` folder** and run one of:

```bash
# Linux / macOS / WSL
./install.sh my-dealbd
```

```powershell
# Windows PowerShell
.\install.ps1 my-dealbd
```

The script creates a fresh Laravel 13 application, copies the overlay into it and generates the application key. You must still set your database and run migrations.

## Quick start (Linux, macOS, WSL)

Unzip this package alongside the Laravel installation directory, then:

```bash
composer create-project laravel/laravel dealbd "^13.0"
cp -R dealbd-starter/. dealbd/
cd dealbd
cp .env.example .env   # usually already created by create-project
php artisan key:generate
```

For the easiest local database, use `DB_CONNECTION=sqlite` in `.env`, create `database/database.sqlite` if missing, then:

```bash
php artisan migrate
php artisan db:seed
php artisan serve
```

Open **http://127.0.0.1:8000**. Register a regular user from `/register`.

### Creating an admin safely

**Do not publish a predefined admin password or modify the registration form to accept `role`.** In your local `.env`, set:

```env
SEED_ADMIN_EMAIL=your-admin-email@example.com
SEED_ADMIN_PASSWORD=replace-with-a-long-random-password
```

Then run `php artisan db:seed`. The seeder creates that user if missing and gives it admin role. If the user already exists, the seeder updates only the role, not the password: reset the password separately if necessary. Delete these two temporary environment settings after successful bootstrap (and rotate any secrets exposed in logs or chat).

## MySQL setup

Create an empty `dealbd` schema/database and set `.env`:

```env
APP_NAME=DealBD
APP_URL=http://localhost:8000
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=dealbd
DB_USERNAME=dealbd
DB_PASSWORD=your-db-password
QUEUE_CONNECTION=database
DEALBD_TIMEZONE=Asia/Dhaka
```

Run `php artisan migrate --seed` and `php artisan serve`. For async alert notifications:

```bash
php artisan queue:work
```

For deal expiry in production, run Laravel's scheduler via cron or the supplied Docker scheduler:

```bash
* * * * * cd /path/to/dealbd && php artisan schedule:run >> /dev/null 2>&1
```

For local prototyping with `QUEUE_CONNECTION=sync`, alerts are created without a worker. In production, use an asynchronous queue and monitor failed jobs.

## Docker development setup

Use **[DOCKER_SETUP.md](DOCKER_SETUP.md)** for the installation sequence, phpMyAdmin details, admin account setup and common troubleshooting. A Docker-only `bootstrap` service installs the Laravel base without needing local PHP/Composer. Do **not** use `install.ps1` simultaneously with this method.

## Directory layout

```text
dealbd-starter/
├── config/dealbd.php  # Time zone configuration
├── app/
│   ├── Console/Commands/ExpireDeals.php
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/ModerationController.php
│   │   │   ├── AuthController.php
│   │   │   ├── HomeController.php
│   │   │   ├── DealController.php
│   │   │   ├── VoteController.php
│   │   │   ├── CommentController.php
│   │   │   ├── BookmarkController.php
│   │   │   ├── ReportController.php
│   │   │   ├── DealAlertController.php
│   │   │   ├── NotificationController.php
│   │   │   ├── DashboardController.php
│   │   │   ├── VisitController.php
│   │   │   └── MerchantController.php
│   │   └── Requests/StoreDealRequest.php
│   ├── Models/
│   ├── Notifications/MatchingDealNotification.php
│   ├── Services/DealAlertService.php
│   └── Providers/AppServiceProvider.php
├── database/
│   ├── migrations/2026_11_03_000001_create_dealbd_tables.php
│   └── seeders/DatabaseSeeder.php
├── resources/views/
│   ├── layouts, partials, auth, deals, dashboard,
│   └── admin, alerts, merchant, notifications
├── public/css/dealbd.css
├── routes/web.php
├── routes/console.php
├── tests/Feature/DealFlowTest.php
├── install.sh / install.ps1
├── Dockerfile
├── docker-compose.yml
├── docker/nginx/default.conf
└── README.md
```

## Core database tables

| Table | Purpose |
|---|---|
| users | Accounts and roles (`member`, `merchant`, `moderator`, `admin`) |
| categories | Offer categories |
| stores | Store directory |
| deals | Offer data, status, price, coupon, payment, expiry |
| deal_votes | Unique per user/deal positive or negative vote |
| comments | Discussion on individual deals |
| bookmarks | Saved deals |
| deal_reports | User abuse/inaccuracy reports |
| deal_alerts | Saved keyword/category/maximum-price rules |
| price_histories | Observations from posts/edits, not automatic scraping |
| notifications | User notification inbox |

## Public routes

- `GET /` — main deal feed
- `GET /deals/{slug}` — details
- `GET /deals/{slug}/visit` — outbound store URL (click counter)
- `GET /register`, `GET /login` — accounts
- `GET /dashboard`, `/deals/create`, `/alerts`, `/notifications` — authenticated tools
- `GET /admin` — moderator/admin dashboard
- `GET /merchant` — merchant analytics

## Security and production checklist

The starter includes basic validation, session authentication, CSRF protection, route permission checks, rate limits on key writes, escaped Blade output, safe outbound URL scheme validation, and database uniqueness constraints.

Before deploying for real customers, also implement:

1. Email verification, password reset, bot mitigation and account lockout/abuse detection.
2. Security review and penetration testing, including XSS/SSRF/open-redirect/header checks.
3. Domain allow-list or merchant-claim verification for external offer links and images.
4. More comprehensive content moderation, email verification for posters, and anti-spam scoring.
5. Proper automated tests, accessibility review, rate limits on all endpoints, logging and audit trails.
6. Search indexing/pagination and cache optimization for larger data sets.
7. Disclosed affiliate redirect tracking, privacy policy, legal terms and appropriate Bangladesh-specific compliance review.
8. Monitoring, backups, deployment automation, disaster recovery and secret management.

## Not yet implemented (proposed advanced modules)

| Module | Expected approach |
|---|---|
| Full standalone forums | Threads, replies, tags, abuse reports, community reputation |
| Automatic price history | Authorized merchant feeds/APIs, standardized SKUs, background workers, historical chart |
| Smart notifications | Email/push delivery, matching/ranking, subscription digests |
| Real affiliate reporting | Approved affiliate partners, conversion callback, fraud prevention |
| Browser extension | Separate Chrome/Firefox extension calling authenticated APIs |
| Personalized recommendations | Product preferences, events, ranking and testing |
| Mobile app | Dedicated mobile API and Flutter/React Native client |
| Seller onboarding | Store verification, merchant billing, subscription plans |
| Advanced operational tools | Audit log, bulk moderation, analytics, feature flags, spam detection |

These features **are not included as working code** in this package. They need separate implementation, testing, integration agreements and infrastructure.

## Tests

From the completed Laravel installation:

```bash
php artisan test --filter=DealFlowTest
php artisan route:list
php artisan deals:expire
```

The overlay was PHP syntax-checked, but the fully integrated application could not be end-to-end executed when packaged because Composer/Laravel dependencies were not installed in the packaging environment. Run tests and resolve any version-specific issues before delivery.

## Ownership

No third-party UI/code from Slickdeals or OzBargain is included. Merchant names in the seeder are examples/directory references and do not imply affiliations. Replace placeholder branding and legal copy before launch.
