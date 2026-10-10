# The Complete Point-of-Sale System

A CodeIgniter 4 midterm project with protected product, customer, staff and sales management.

## Features

- Staff login/logout and authentication filter
- Product CRUD through safe archiving, with image upload
- Customer CRUD through safe archiving
- Staff CRUD, avatar upload and hashed passwords
- Atomic sale recording with server-calculated totals
- Conditional stock deduction that prevents overselling
- Sales history with product, customer and staff joins
- CSRF protection, validation and escaped output
- Migrations, demo seeder and SQL schema export

## Requirements

- PHP 8.2+
- Composer
- MySQL/MariaDB
- Required CodeIgniter PHP extensions (`intl`, `mbstring`, `mysqli`)

## Installation

```bash
git clone YOUR_REPOSITORY_URL pos-midterm
cd pos-midterm
composer install
```

Copy `env` to `.env`, then update the database settings. Create an empty database named `pos_midterm`, then run:

```bash
php spark migrate
php spark db:seed DemoSeeder
php spark serve
```

Open `http://localhost:8080`.

## Development Login

- Username: `admin`
- Password: `Admin123!`

Change this password before public deployment.

## Upload Directories

Ensure these directories are writable by PHP:

- `public/uploads/products`
- `public/uploads/avatars`
- `writable`

Uploads are limited to JPG, PNG and WebP images up to 2 MB. Uploaded user files are ignored by Git.

## Database

The preferred setup uses the migrations in `app/Database/Migrations`. A matching raw schema is also supplied in `database/pos_midterm.sql` for submission requirements.

## Production

Set the web-server document root to `public`, use production database credentials in `.env`, then run:

```bash
composer install --no-dev --optimize-autoloader
php spark migrate --all
php spark env production
```

Never commit `.env` or production credentials.

## Submission Links

- GitHub repository: `ADD_GITHUB_LINK_HERE`
- Hosted application: `ADD_HOSTED_LINK_HERE`
