# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

InkluSyncID is a Laravel Identifikasi Anak Berkebutuhan Khusus (ABK) Sekolah Dasar. The codebase was originally "Ferensa Studio" and is being rebuilt/rebranded as InkluSyncID. The frontend design is in `InkluSyncID.html` (fetched from the design URL in `notes.md`). Auction-related legacy code from Ferensa Studio should be removed once InkluSyncID features are fully implemented.

## Commands

```bash
# Development server (use Laragon instead — it auto-serves at http://inklusyncid.test)
php artisan serve

# Frontend assets
npm run dev       # Vite dev server with HMR
npm run build     # Production build

# Database
php artisan migrate
php artisan db:seed

# Tests
./vendor/bin/phpunit
./vendor/bin/phpunit --filter TestName      # Single test
./vendor/bin/phpunit tests/Feature          # Feature suite only

# Task scheduler (must be running for auction status updates)
php artisan schedule:run
```

## Architecture

### Controller Organization by Role

Routes and controllers are segmented strictly by user role:

| Prefix        | Role         | Controllers in                     |
| ------------- | ------------ | ---------------------------------- |
| `/`           | Public       | `App\Http\Controllers\Home\`       |
| `/superadmin` | `superadmin` | `App\Http\Controllers\Superadmin\` |
| `/admin-new`  | `admin`      | `App\Http\Controllers\AdminNew\`   |
| `/member`     | `member`     | `App\Http\Controllers\Member\`     |

All protected routes use `middleware(['auth', 'role:{role}'])`. Role enforcement is in `App\Http\Middleware\RoleMiddleware`.

### Authentication

Single entry point: `UnifiedAuthController` handles login/register/logout for all roles. After login, users are redirected to their role-specific dashboard. No separate auth guards per role — role is stored on the `users` table.

### View Structure

Views mirror the controller structure: `resources/views/{home,member,superadmin,admin_new}/`. Each section has its own `layout/` and `partials/` subdirectories. There is no shared master layout across roles — each role has its own.

### Key Models & Relationships

- `BatchClass` → belongs to `MasterClass`, `Instructor`, `Room`; has many `Booking`
- `Booking` → belongs to `User`, `BatchClass`; part of `Transaction`
- `Transaction` → has many `TransactionItem`; belongs to `User`; covers both bookings and product orders
- `Cart` / `AbandonedCart` → per-user, items can be bookings or products
- `Subscription` → purchased plans that grant booking credits

### Helpers

Global helpers are auto-loaded via `composer.json`:

- `app/Helpers/formatHelpers.php` — date/time formatting
- `app/Helpers/currency_helpers.php` — Rupiah formatting

### Frontend Stack

Blade templates + Vite. Tailwind CSS is referenced in the design but check `resources/css/app.css` and `vite.config.js` for what's actually bundled. SweetAlert2 (via `sweetalert` package) is used for confirmations and alerts.

## Database

- **DB:** `inklusyncid` (MySQL, localhost:3306, root/root per `.env`)
- Migrations are in `database/migrations/` — 22 total as of April 2026
- Seeders in `database/seeders/` — run `DatabaseSeeder` which orchestrates all seeders
- `CmsSeeder` populates `cms_pages` and `cms_sections` for the content management system

## Pending Work (from notes.md)

1. Implement InkluSyncID frontend design (replacing Ferensa Studio public pages)
2. Implement superadmin dashboard matching the new InkluSyncID theme
3. Remove all auction/Ferensa-related code: `Admin/` controllers, auction models, auction seeders, `UpdateAuctionStatus` command
