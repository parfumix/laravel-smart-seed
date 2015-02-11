# Laravel smart seeder

[![Join the chat at https://gitter.im/parfumix/laravel-smart-seed](https://badges.gitter.im/Join%20Chat.svg)](https://gitter.im/parfumix/laravel-smart-seed?utm_source=badge&utm_medium=badge&utm_campaign=pr-badge&utm_content=badge)

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

## Add new source seeders.

To add new sources you have to enter command below 
```bash
php artisan smart:seed create user,page,news
```
Each of the name have to be identical existent Eloquent model. 

To run all created seeder sources enter
```bash
php artisan smart:seed run
```

## Set up configuration file

To add new providers go to app/seeds.php configuration file and add provider

```php
 'providers' => array(
        'yaml' => array(
            'path'   => config_path('seeds/yaml'),
            
            #enter an provider which will create an seed resource and rn
            'class'  => LaravelSeed\Providers\YamlProvider::class,
            
            #or add closure functions
            'run' => function() {
              // run all your migrations from provider path
            },
            
            'create' => function($source, $class) {
              // create an resource seed
            }
        )
    )
```

## Set up an default provider

To set up an default provider go to app/seeds.php

```php
  # enter an existing provider.
  'default' => 'yaml',
```

### License

Laravel smart seeder is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT)
