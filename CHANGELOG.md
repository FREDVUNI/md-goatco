# Changelog

All notable changes to the MD Goatco Farm Limited platform are logged here.

## 2026-06-21

### Changed — Auth: consolidated login flow

Replaced four separate role-specific login forms with a single login used by every role. Which dashboard a user lands on is now decided by `users.role` after authentication, not by which form they visited.

- **Routes** (`app/Config/Routes.php`): removed `auth/admin`, `auth/manager`, `auth/vet` as live login routes. `auth/login` (GET/POST) now handles every role. The three old URLs still resolve — they redirect to `auth/login` — so any existing bookmarks/links don't break.
- **Controller** (`app/Controllers/AuthController.php`): collapsed 8 methods (`loginMember`, `doLoginMember`, `loginAdmin`, `doLoginAdmin`, `loginManager`, `doLoginManager`, `loginVet`, `doLoginVet`, plus the shared `doStaffLogin` helper) down to `login()` + `doLogin()`. Added `dashboardUrlForRole()`, which maps `super_admin` → `/admin/dashboard`, `manager` → `/manager/dashboard`, `vet` → `/vet/dashboard`, `member` → `/member/dashboard`. Dropped the "wrong portal for your role" check — with one form there's no wrong form to land on.
- **View**: `app/Views/auth/login_member.php` renamed to `app/Views/auth/login.php`. Removed the "Other portals" chip links (Super Admin / Farm Manager / Veterinary). Softened "Goat Banking Member Portal" / "Goat Banking dashboard" copy to be role-neutral, since staff now see this same page. Deleted the now-unused `login_admin.php`, `login_manager.php`, `login_vet.php`.

### Fixed — local environment: database connectivity

`php spark migrate` and `php spark db:seed` were failing with no usable error message ("The error view file was not specified. Cannot display error view."). Root-caused to two stacked issues:

1. **Missing CLI error view.** `app/Views/errors/cli/error_exception.php` didn't exist, so CodeIgniter's exception handler had no template to render — it was swallowing every real error, including the actual DB connection failure underneath. Restored a minimal working version of this file.
2. **Wrong DB host/port for MAMP.** The project's local MySQL is managed by MAMP, which runs on port **8889** by default — not the standard 3306, and not reachable via the `localhost` socket path CI4/mysqli expects out of the box. `.env` was updated:
   ```dotenv
   database.default.hostname = 127.0.0.1
   database.default.port     = 8889
   database.default.password = root   # MAMP default
   ```
   `127.0.0.1` (rather than `localhost`) forces mysqli to connect over TCP instead of hunting for a Unix socket file, which sidesteps socket-path mismatches entirely.

`php spark migrate:status` now connects successfully and correctly lists both pending migrations.

### Known issues — blocking next steps

- **`db:seed` will fail until this is fixed:** `app/Database/Seeds/UserSeeder.php` declares `namespace App\Database\Seeders;` but lives in the `Seeds/` folder. Must be changed to `namespace App\Database\Seeds;` to satisfy PSR-4 autoloading.
- **Module controllers won't autoload as currently structured.** Several files under `app/Modules/**` bundle multiple classes in one file (e.g. `VetControllers.php` contains `DashboardController`, `VisitController`, and `FlagController`; similarly for `Member`, `Admin`, `Manager`, and `Api` modules — ~25 classes affected in total). PSR-4 requires one class per file, filename matching class name. None of these routes will resolve their controllers until each class is split into its own file. Not yet addressed.

### Migrations — status as of this entry

`php spark migrate:status` confirms both migrations are detected but **not yet run**:

| Namespace | Version           | Filename         | Migrated |
| --------- | ----------------- | ---------------- | -------- |
| App       | 2026_06_15_000001 | CreateCoreSchema | Pending  |
| App       | 2026_06_20_000001 | AddPaymentsTable | Pending  |

Next: run `php spark migrate --all`, fix the `UserSeeder` namespace above, then `php spark db:seed DatabaseSeeder`.
