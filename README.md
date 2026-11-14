# poshmedica.co.th

Website of Posh Medica Lifetech Public Company Limited, in Thai and English. Laravel 12, PHP 8.3, Filament 5 admin at `/admin`.

To install and run it, follow [HANDOVER.md](HANDOVER.md).

To run it on a laptop with SQLite:

```
composer install
cp .env.example .env    # set DB_CONNECTION=sqlite and remove the other DB_ lines
php artisan key:generate
touch database/database.sqlite
php artisan migrate
php artisan storage:link
php artisan filament:assets
php artisan posh:import
php artisan make:filament-user
php artisan serve
```

Then open http://127.0.0.1:8000/th/home and http://127.0.0.1:8000/admin.
