# MD Goatco Farm Limited — Platform

Full-stack web application + REST API for the MD Goatco Farm Goat Banking system.

Built with **CodeIgniter 4** — serves web dashboards for 4 roles, a versioned REST API for mobile apps / PWAs, branded transactional email, and Pesapal-powered wallet top-ups.

---

## Stack

| Layer | Technology |
|---|---|
| Framework | CodeIgniter 4.5 |
| Language | PHP 8.1+ |
| Database | MySQL 8.0 / MariaDB 10.6+ |
| Auth (web) | CI4 Sessions + role filter |
| Auth (API) | JWT (firebase/php-jwt) |
| Payments | Pesapal API v3 (cards, mobile money, bank) |
| Email | CI4 Email service + branded HTML templates |
| File storage | Local (writable/uploads/) — private, outside webroot |
| PDF export | DomPDF |
| Excel export | PhpSpreadsheet |
| Hosting | Shared cPanel / VPS (any PHP 8.1+ host) |

---

## Quick Start

```bash
git clone <your-repo> mdgoatco
cd mdgoatco
composer install
cp .env.example .env
```

Edit `.env`:
- Set `app.baseURL`
- Set database credentials (`database.default.*`)
- Generate and set `JWT_SECRET` (min 32 chars):
  ```bash
  php -r "echo bin2hex(random_bytes(32));"
  ```
- Generate and set `encryption.key`:
  ```bash
  php spark key:generate
  ```
- Configure SMTP settings under `email.*` (see [Email](#email--notifications) below)
- Configure Pesapal settings under `pesapal.*` (see [Payments](#payments--pesapal) below)

### Database

```bash
# Create the database first
mysql -u root -p -e "CREATE DATABASE mdgoatco CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"

# Run migrations (creates all 9 tables)
php spark migrate

# Seed a realistic demo dataset — admin, staff, members in every
# application state, a working herd, vet history, transactions,
# sample payments, notifications
php spark db:seed DatabaseSeeder
```

### Run locally

```bash
php spark serve
# → http://localhost:8080
```

That's it — `composer install` pulls in CodeIgniter 4 and every dependency this app needs; there's no separate Node/CI build step and nothing else to compile.

---

## Default Credentials (seeded — change immediately in a real deployment)

| Role | URL | Email | Password |
|---|---|---|---|
| Super Admin | `/auth/admin` | admin@mdgoatco.farm | Admin@2026! |
| Farm Manager | `/auth/admin` | manager@mdgoatco.farm | Manager@2026! |
| Farm Manager | `/auth/admin` | grace.farm@mdgoatco.farm | Manager@2026! |
| Veterinarian | `/auth/admin` | vet@mdgoatco.farm | Vet@2026! |
| Veterinarian | `/auth/admin` | dr.namatovu@mdgoatco.farm | Vet@2026! |
| Member (approved) | `/auth/login` | esther.nakato@example.com | Member@2026! |
| Member (pending) | — | patricia.achieng@example.com | Member@2026! |
| Member (rejected) | — | florence.birungi@example.com | Member@2026! |
| Member (info requested) | — | james.ouma@example.com | Member@2026! |

The seeder creates 8 approved Goat Banking members (each with goats already assigned, a wallet balance, and transaction history), 2 pending applications, 1 rejected application, and 1 info-requested application — so the admin Applications queue and every dashboard has real data to click through immediately. See `app/Database/Seeds/DatabaseSeeder.php` for the complete list and to extend it further.

---

## Email & Notifications

All transactional email is sent through `App\Libraries\Mailer`, a thin wrapper around CodeIgniter's built-in Email service. **Customizing templates is editing one PHP file per email** — no build step:

```
app/Views/emails/
├── layout.php                     ← shared header/footer/branding — edit once to re-brand ALL emails
├── application_received.php       ← sent when someone applies
├── application_approved.php
├── application_rejected.php
├── application_info_requested.php
├── password_reset.php
├── staff_account_created.php      ← includes the temporary password
├── staff_password_reset.php
├── contact_admin_notification.php ← public contact form → admins
├── contact_autoreply.php          ← public contact form → sender
├── support_request_admin.php      ← member support form → admins
├── new_application_admin_alert.php
├── payment_confirmed.php          ← Pesapal wallet top-up succeeded
└── payment_failed.php             ← Pesapal wallet top-up failed
```

To change the logo, colours, or footer for every email, edit `layout.php`. To change the wording of one notification, edit its own template.

**SMTP configuration** lives in `.env`:

```ini
email.protocol   = smtp
email.SMTPHost   = smtp.gmail.com
email.SMTPUser   = hello@mdgoatco.farm
email.SMTPPass   = your-app-password      # Gmail: use an App Password, not your real password
email.SMTPPort   = 587
email.SMTPCrypto = tls
email.fromEmail  = hello@mdgoatco.farm
email.fromName   = MD Goatco Farm Limited
```

`Mailer::send()` never throws — if SMTP isn't configured yet (e.g. on a fresh local install with no credentials in `.env`), it logs the failure to `writable/logs/` and the request continues normally. Registration, approvals, password resets, and payments all keep working even when mail genuinely can't go out; you just won't see the email land anywhere. Tail the log to confirm what *would* have been sent:

```bash
tail -f writable/logs/log-$(date +%Y-%m-%d).log
```

---

## Payments — Pesapal

Members top up their Goat Banking wallet via [Pesapal](https://www.pesapal.com) — supports mobile money, cards, and bank transfer across East Africa.

### Setup

1. Create a free sandbox account at [developer.pesapal.com](https://developer.pesapal.com) to get test Consumer Key/Secret.
2. Add them to `.env`:
   ```ini
   pesapal.consumerKey    = your_sandbox_key
   pesapal.consumerSecret = your_sandbox_secret
   pesapal.environment    = sandbox
   pesapal.currency       = UGX
   ```
3. Members can now top up from **Member → Top Up Wallet**.

When you're ready to accept real payments, switch `pesapal.environment` to `live` and use your verified merchant account's production credentials — no code changes needed.

### How it works

- `App\Libraries\PesapalLibrary` wraps the Pesapal API v3 (auth token, IPN registration, order submission, status checks) using CodeIgniter's built-in CURLRequest — no extra Composer package required.
- A member's top-up request creates a `payments` row (status `pending`), then redirects them to Pesapal's hosted payment page.
- Pesapal calls **`/payments/ipn`** (server-to-server) the moment a payment's status changes — this is the source of truth. Pesapal also redirects the member's browser back to **`/payments/callback`** for a fast UX update, but that page independently re-verifies the real status with Pesapal rather than trusting the redirect parameters.
- Once Pesapal confirms a payment `COMPLETED`, the member's wallet is credited (a `transactions` row is created), they're emailed a confirmation, and an in-app notification is sent.
- The `payments` table keeps every attempt — pending, completed, failed, invalid, or reversed — for a full audit trail, visible to admins under **Admin → Wallet Top-ups**.

**Local development note:** Pesapal's IPN is a real server-to-server call, so it can't reach `http://localhost:8080`. The browser callback (`/payments/callback`) still works fine locally since it's the member's own browser making that request, so end-to-end testing works even without a public URL — use a tool like [ngrok](https://ngrok.com) if you specifically need to test the IPN path.

---

## Project Structure

```
mdgoatco/
├── app/
│   ├── Config/
│   │   ├── Routes.php           ← All routes (web + API + payments)
│   │   ├── Pesapal.php          ← Pesapal credentials & environment
│   │   ├── Email.php            ← SMTP settings
│   │   └── ...                  ← full set of CodeIgniter framework configs
│   │
│   ├── Controllers/
│   │   ├── BaseController.php   ← Shared helpers (session, view, redirect)
│   │   ├── AuthController.php   ← Logins, register, logout, password reset
│   │   ├── PublicController.php ← Landing page, about, services, contact
│   │   └── PaymentController.php← Pesapal callback + IPN endpoints
│   │
│   ├── Filters/                 ← RoleFilter, JwtFilter, CorsFilter, GuestFilter
│   ├── Models/                  ← UserModel, GoatModel (+VetVisit/WeightLog),
│   │                               MemberApplicationModel, TransactionModel
│   │                               (+Notification), PaymentModel
│   ├── Libraries/
│   │   ├── Mailer.php           ← Branded HTML email sender
│   │   ├── PesapalLibrary.php   ← Pesapal API v3 client
│   │   ├── JwtLibrary.php
│   │   └── FileUploader.php
│   │
│   ├── Modules/
│   │   ├── Admin/Controllers/   ← Dashboard, Applications, Staff, Herd,
│   │   │                            Settings, Payments oversight
│   │   ├── Manager/Controllers/ ← Dashboard, Herd, Health, Schedule, Reports
│   │   ├── Vet/Controllers/     ← Dashboard, Visits, Animals, Tasks, Flags
│   │   ├── Member/Controllers/  ← Dashboard, Portfolio, Statements, Wallet,
│   │   │                            Account, Support
│   │   └── Api/Controllers/V1/  ← Full mobile REST API (all roles + payments)
│   │
│   ├── Views/
│   │   ├── layouts/             ← dashboard.php, auth.php, public.php
│   │   ├── emails/               ← All branded email templates
│   │   ├── public/, auth/, admin/, manager/, vet/, member/
│   │
│   └── Database/
│       ├── Migrations/          ← Schema (core tables + payments)
│       └── Seeds/DatabaseSeeder.php
│
├── public/                      ← Web root (point your server here)
├── writable/                    ← logs, cache, sessions, uploads (private)
├── spark                        ← CLI entry point (php spark migrate, etc.)
├── .env.example
└── composer.json
```

---

## Role & Access Matrix

| Area | Super Admin | Manager | Vet | Member |
|---|---|---|---|---|
| `/admin/*` | ✓ Full | ✗ | ✗ | ✗ |
| `/manager/*` | ✓ Full | ✓ Full | ✗ | ✗ |
| `/vet/*` | ✓ Full | ✓ Read | ✓ Full | ✗ |
| `/member/*` | ✗ | ✗ | ✗ | ✓ (if approved) |
| `/payments/callback`, `/payments/ipn` | Public — Pesapal calls these directly, no session |
| `/api/v1/*` | ✓ | ✓ | ✓ | ✓ (if approved) |

**Member approval gate**: Members with `status = pending` or `status = rejected` cannot log in even with correct credentials. Super Admin must approve the application first.

---

## API Usage

### Authentication

```bash
POST /api/v1/auth/login
Content-Type: application/json
{"email": "member@example.com", "password": "password"}
```

```json
{
  "status": "success",
  "data": {
    "access_token": "eyJ...",
    "refresh_token": "eyJ...",
    "expires_in": 3600
  }
}
```

### Authenticated requests

```bash
GET /api/v1/member/goats
Authorization: Bearer eyJ...
```

### Wallet top-up (mobile)

```bash
POST /api/v1/payments/topup
Authorization: Bearer eyJ...
Content-Type: application/json
{"amount": 200000}

# → { "redirect_url": "https://...", "merchant_reference": "GBANK-..." }
# Open redirect_url in an in-app browser/webview to complete payment.

GET /api/v1/payments/GBANK-XXXXXXXXXX
Authorization: Bearer eyJ...
# → { "status": "completed" | "pending" | "failed" | ... }
```

### Key endpoints by role

```
Member:  GET  /api/v1/member/dashboard | goats | goats/:id | statements | notifications
         POST /api/v1/member/support

Vet:     GET  /api/v1/vet/tasks | visits | flags
         POST /api/v1/vet/tasks/:id/complete | visits | flags/:id/resolve

Manager: GET  /api/v1/manager/herd | herd/:id | health-flags | schedule | reports/:type

Admin:   GET  /api/v1/admin/applications | members | staff
         POST /api/v1/admin/applications/:id/approve | reject | staff
```

---

## File Upload Security

Uploaded documents (NID scans, headshots) are stored in `writable/uploads/`, **outside the public webroot**, and can't be accessed directly via URL. They're served only through `Admin\DocumentController::serve()` at `/admin/documents/:applicationId/:docType`, which checks the requester is a super admin before streaming the file.

---

## Production Deployment (cPanel shared hosting)

1. Upload all files **except** `public/` to a folder above the webroot (e.g. `/home/user/mdgoatco/`)
2. Upload **only** the contents of `public/` to `public_html/` (or your subdomain folder)
3. If your app folder ends up somewhere other than directly above `public/`, edit **`app/Config/Paths.php`** (not `public/index.php`) to point `$appDirectory`, `$systemDirectory`, and `$writableDirectory` at the right absolute paths.
4. Create a MySQL database in cPanel and update `.env`
5. Run `composer install --no-dev --optimize-autoloader` on the server (or upload `vendor/` from a matching PHP-version build)
6. Run migrations + seed via SSH: `php spark migrate` (skip the seeder in production — it creates demo accounts with known passwords)
7. Set `CI_ENVIRONMENT = production` in `.env`
8. Enable `app.forceGlobalSecureRequests = true`
9. Set `pesapal.environment = live` with your verified production credentials
10. Add SSL certificate (Let's Encrypt via cPanel)
11. Your production IPN URL registers itself with Pesapal automatically the first time a real payment is submitted — `PesapalLibrary::getIpnId()` registers and caches it using whatever `app.baseURL` resolves to.

---

## What Was Fixed

This scaffold previously couldn't run locally — these were the structural issues found and corrected, in case anything looks unfamiliar from an earlier version of this codebase:

- **`public/index.php` used CodeIgniter's pre-4.5 boot process**, which framework 4.5+ no longer supports (the old `system/bootstrap.php` now just exits with a 503). It also hardcoded `ENVIRONMENT = production` *before* the framework could read `.env`, which would have suppressed every error behind a blank page even after fixing the boot process. Rewritten to use the modern `Boot::bootWeb()` + `app/Config/Paths.php` pattern.
- **The `spark` CLI script didn't exist at all** — `php spark migrate`, `db:seed`, `serve`, and `key:generate` had no way to run.
- **Most of `app/Config/`'s default files were missing** (`Paths.php`, `Constants.php`, `Modules.php`, `Services.php`, `Boot/*.php`, and ~25 others CodeIgniter requires to exist). Without them the framework can't boot under 4.5+.
- **Routes for every dashboard (admin/manager/vet/member) were missing their `namespace` option**, so every route resolved to a non-existent class under `App\Controllers\*` instead of the actual `App\Modules\*\Controllers` classes — every dashboard page would 404.
- **Migrations and seeds lived in `database/`** at the project root; CodeIgniter only discovers them under `app/Database/Migrations` and `app/Database/Seeds`, so `php spark migrate` silently found nothing.
- **`Config\Migrations::$timestampFormat`** didn't match the actual underscore-separated migration filename, which would also have broken discovery once the directory was fixed.
- **`Config\Filters` extended the wrong base class** and was missing the `$required` filters array — under 4.5+ this is needed for core request handling and would affect every single request.
- **`.env.example` set `session.savePath = WRITEPATH . 'session'`** — that's PHP code sitting in a plain-text `.env` file, so it would be read as a literal string and break file-based sessions the moment someone copied it to `.env`.
- Several `App.php` / `Security.php` / `Session.php` properties the framework expects (`appTimezone`, `permittedURIChars`, `tokenRandomize`, etc.) weren't declared, and `App::$proxyIPs` was typed as a `bool` instead of an `array`.
- A leftover `{app/{Config,Controllers,...}}` directory from a shell scripting mistake (unexpanded brace-expansion) sat in the project root and has been removed.
- 19 views referenced by working controllers didn't exist (`public/about`, `public/contact`, every admin/manager/vet "detail" page, etc.) — all have been built out in the existing visual style.
- Several routes used `(:alpha)`/`(:alphanum)` placeholders for values that actually contain underscores or hyphens (e.g. document types like `nid_front`, payment references like `GBANK-xxxx`) — fixed to `(:segment)` where needed.

On top of the fixes above: email notifications, Pesapal payments, the missing views, and the expanded seed data described elsewhere in this README are all new.
