# Personal Portfolio — Laravel + MySQL

## Requirements
- Laragon with PHP 8.2+ (Laravel 12 compatible), MySQL, Composer
- Internet connection for Composer and Bootstrap CDN (or download Bootstrap assets locally)

## Install on Windows
1. Extract the ZIP to `D:\laragon\www\personal-portfolio`.
2. Start **Apache** and **MySQL** in Laragon.
3. Create database `personal_portfolio` using HeidiSQL/phpMyAdmin or SQL: `CREATE DATABASE personal_portfolio CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;`.
4. Open **Laragon Terminal**, run:

```bat
cd /d D:\laragon\www\personal-portfolio
composer install
copy .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan storage:link
php artisan portfolio:admin admin@example.com
php artisan optimize:clear
php artisan serve
```

5. Set a private admin email and strong password (12+ characters) at the prompt. Never publish it.
6. Public site: `http://127.0.0.1:8000`
7. Admin login: `http://127.0.0.1:8000/admin/login`

### Database setup
Edit `.env` BEFORE running migrations if your MySQL username/password differs from the default Laragon root/blank password. `.env` should contain `DB_CONNECTION=mysql`, `DB_DATABASE=personal_portfolio`, and correct credentials.

### Notes
- Laravel framework dependencies are **not bundled**: `composer install` downloads them.
- Admin edits persist in MySQL and appear on the public site after refresh. Images use Laravel's `public` storage disk and require `php artisan storage:link`.
- For Apache/Laragon auto virtual hosts, point the document root to `personal-portfolio/public`; **never** serve the project root.
- Bootstrap is loaded from a CDN, so offline styling requires replacing the CDN reference with a local copy.
- This is an initial working source implementation; full integration tests on your Laragon machine are still required.
- Included: CRUD for projects, skills, services, experience, education, testimonials; editable profile, hero, settings; messages; admin account; image/CV uploads.
- Not included yet: multiple screenshots per project, drag-and-drop ordering, theme toggle, advanced SEO, and production email notifications.


## First-time administrator registration
Run `php artisan migrate`, then open `/admin/register` to create the first admin. This URL is disabled after an administrator exists. Then sign in at `/admin/login`. Existing administrators can continue signing in normally. Password reset email requires working Laravel `MAIL_*` configuration; `MAIL_MAILER=log` writes reset links to local logs for development. Hero personal portrait is editable under Admin > Hero > Personal Image. Run `php artisan storage:link` to display uploaded images.
