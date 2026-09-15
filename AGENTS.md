# AGENTS.md - RT/RW Letter Submission System

## Quick Reference

```bash
# Dev server
php artisan serve --host=127.0.0.1 --port=8000

# Run tests (needs DB_CONNECTION=sqlite in .env or phpunit.xml)
php artisan test
php artisan test --filter=PengajuanSecurityTest

# Build frontend
npm run dev    # watch mode
npm run build  # production

# Database
php artisan migrate:fresh --seed
```

## Architecture

Laravel 9.x app for RT/RW (neighborhood) letter submissions with multi-step approval.

**Key directories:**
- `app/Http/Controllers/PengajuanController.php` - Main approval logic (400+ lines, role-branching)
- `app/Http/Middleware/CheckRole.php` - Role gate: `->middleware('role:rt,rw')`
- `app/Http/Controllers/Api/` - Mobile REST API (Sanctum tokens)
- `resources/views/warga/` - Resident-facing views
- `resources/views/admin/` - Admin/RT/RW dashboards
- `database/migrations/` - Has SQLite-specific migrations (enum handling)

## Roles & Access

4 roles enforced via `CheckRole` middleware:
- **warga** - Can only see own submissions; `/warga/dashboard`
- **rt** - Approves `baru` → `disetujui_rt`; `/admin/rt` or `/dashboard`
- **rw** - Approves `disetujui_rt` → `diterima`; `/admin/rw` or `/dashboard`
- **admin** - User management; `/dashboard` (admin stats view)

**Critical rule:** RT cannot set status to `diterima`. RW can only approve if current status is `disetujui_rt`. These constraints are in `PengajuanController::approve()` and `update()`.

## Status Flow

```
baru → disetujui_rt → diterima
  ↓          ↓
ditolak   ditolak
```

Enum values: `baru`, `disetujui_rt`, `diterima`, `ditolak`

## Database

- `.env.example` defaults to MySQL, but dev typically uses SQLite (`database/database.sqlite`)
- `phpunit.xml` has `DB_CONNECTION=sqlite` commented out - enable it for tests
- SQLite migrations use `DB::getDriverName() === 'sqlite'` checks to skip ENUM statements
- Seeders create accounts: `warga01@example.com` through `warga05@example.com`, `rt01@example.com`, `rw02@example.com`-`rw05@example.com`, `admin@example.com` - all password `12345678`

## Deployment

- **Vercel**: `api/index.php` is the entry point, `vercel.json` routes all requests there
- **VPS**: `deploy/deploy.sh` runs composer, migrate, optimize:clear, cache commands
- **Vercel requirements**: `SESSION_DRIVER=cookie`, `SESSION_SECURE_COOKIES=true` (no SQLite)

## Gotchas

- **Registration accepts any role** - users can self-select warga/rt/rw/admin during signup (not just warga)
- **File uploads** stored at `storage/app/pengajuan_files/` with UUID filenames; `downloadFile()` checks local then public disk
- **Blade layout** varies by role: `warga.dashboard_warga` vs `admin.rt.index` vs `admin.rw.index`
- **Route names** are Indonesian: `ajukan`, `status.show`, `status.approve`, `pengajuan.store`, etc.
- **`/warga`** is a public PWA landing page (no auth required)
- **`/warga/dashboard`** is the authenticated resident dashboard (different from `/warga`)

## Testing

- Tests use `RefreshDatabase` trait with `User::create()` (no factories for most tests)
- `User::factory()` exists for PengajuanSecurityTest but most tests use direct create
- `phpunit.xml` test suites: `tests/Unit/` and `tests/Feature/`
- Coverage includes `app/` directory

## Env Variables to Watch

- `DB_CONNECTION` - Must match your DB setup (sqlite vs mysql)
- `SESSION_DRIVER` - `file` for dev, `cookie` for Vercel
- `APP_DEBUG` - Must be `false` in production (deploy.sh enforces this)
