# AGENTS.md

## Cursor Cloud specific instructions

### Overview
This is a Laravel 11 ophthalmology clinic management system (Almezan Medical System). It uses Livewire 3.7, Tailwind CSS + DaisyUI, and MySQL 8.0.

### System Dependencies (pre-installed in VM snapshot)
- PHP 8.2 with extensions: mbstring, xml, curl, zip, sqlite3, bcmath, intl, mysql
- Composer 2.x
- Node.js 22.x / npm 10.x
- MySQL 8.0

### Database Setup
- The project has **no Laravel migrations**. The schema is managed via raw SQL files.
- Use `database/full_database_fresh.sql` to bootstrap a fresh MySQL database: `sudo mysql laravel < database/full_database_fresh.sql`
- After importing the SQL dump, run `php artisan db:seed --class=PermissionSeeder` to create permissions in the correct dot-notation format (e.g. `view.patients`). The SQL dump uses hyphenated names (`view-patients`) which don't match the codebase.
- The admin password must be rehashed after SQL import. Use tinker: `php artisan tinker --execute="App\Models\User::where('email','admin@gmail.com')->first()->update(['password'=>bcrypt('password123')])"`

### Starting Services
1. **MySQL**: `sudo mysqld --user=mysql --datadir=/var/lib/mysql &` (wait ~5s for startup)
2. **Laravel dev server**: `php artisan serve --host=0.0.0.0 --port=8000`
3. **Vite dev server** (for CSS/JS hot reload): `npm run dev -- --host 0.0.0.0`
4. **Build assets** (alternative to Vite dev): `npm run build`

### Login Credentials (seeded)
- Admin: `admin@gmail.com` / `password123`
- Dr. Alaa: `alaa@almyzan.ps` / `password123`
- Dr. Tariq: `tariq@almyzan.ps` / `password123`

### Commands Reference
- **Lint**: `./vendor/bin/pint --test` (41 pre-existing style issues)
- **Tests**: `php artisan test` (1 pre-existing failure in default ExampleTest due to auth redirect)
- **Build**: `npm run build`

### Gotchas
- The `.env.example` defaults to SQLite, but this project requires MySQL because the schema is only available as MySQL SQL dumps (no Laravel migrations, no SQLite schema).
- The `database/schema/mysql-schema.sql` is a Laravel schema dump but is older than `database/full_database_fresh.sql`; prefer the latter for a complete working setup.
- Permission names in the SQL dump use hyphens (`view-patients`) but the code expects dots (`view.patients`). Always run `PermissionSeeder` after importing from SQL.
- The DoctorSeeder references an `email` column not present in the current schema; avoid running the full `DatabaseSeeder` after SQL import. Use `PermissionSeeder` directly instead.
