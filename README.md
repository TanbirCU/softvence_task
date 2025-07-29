# Course Creation App

This is a Laravel app to create courses with multiple modules and contents.

## Features

- Add unlimited modules inside a course
- Add unlimited contents (text/image/video/link) inside each module
- Full AJAX frontend (jQuery) with nested input UI
- Validation and database saving

## Setup

```bash
git clone https://github.com/TanbirCU/softvence_task
cd softvence_task
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan serve

