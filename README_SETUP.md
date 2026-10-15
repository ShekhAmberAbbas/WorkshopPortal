# Quaere Tech Hub — Workshop Registration Portal

## Fresh installation

1. Copy `.env.example` to `.env` and update the MySQL credentials.
2. Create the `workshop` database if it does not already exist.
3. Run:

```powershell
composer install
php artisan key:generate
php artisan optimize:clear
php artisan migrate:fresh --seed
php artisan storage:link
npm install
npm run build
php artisan serve
```

The project uses a **create-only migration layout**: each table is created once in its own migration. There are no `Schema::table()` update migrations and no dynamic feedback tables.

## Default admin

The seed creates:

- Email: `admin@quaeretech.com`
- Password: `Admin@12345`

Set `ADMIN_NAME`, `ADMIN_EMAIL`, and `ADMIN_PASSWORD` in `.env` before running the seeder for a different account.

## Main application rules

- Course and branch lists are managed from **Admin → Academic Options**.
- Branches shown to students are filtered by the selected active course.
- Feedback is optional and fixed in `config/workshop_feedback.php`; there is no admin feedback-question management.
- Feedback answers are stored directly on the participant record and are included in registration exports.
- Certificate numbers use one global sequence: `QTH001`, `QTH002`, `QTH003`, … across all workshops.
- Certificate numbers are never reused after a certificate or participant is deleted because the counter only moves forward.
- Workshop duration is calculated from start/end dates and stored automatically.
