# Laravel smart seeder

## Installation

Begin by installing this package through Composer. Run this command from the Terminal:

```bash
    composer require parfumix/laravel-smart-seed
```

## Laravel integration

To wire this up in your Laravel project, you need to add the service provider. Open `app/config/app.php`, and add a new item to the providers array.

```php
 'LaravelSeed\Laravel5SeedServiceProvider',
```

Then, add you have to register an command . Just go to app/console/kernel and add following line to

```php
protected $commands = [
    'App\Console\Commands\Inspire',
		
    # --- insert that ---
    LaravelSeed\Commands\Seeder::class
];
```

Publish your config file using command and go to your config folder.

```php
php artisan vendor:publish
```
