<?php namespace LaravelSeed;

use Illuminate\Support\ServiceProvider;
use LaravelSeed\Exceptions\SeederException;
use LaravelSeed\Repositories\SeederDbRepository;

class Laravel5SeedServiceProvider extends ServiceProvider {

    protected $commands = [
        'smart:install'   => Commands\Install::class,
        'smart:run'       => Commands\Run::class,
        'smart:create'    => Commands\Create::class,
        'smart:rollback'  => Commands\Rollback::class,
    ];

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot() {

        /** Publish configuration file . */
        $this->publishes([
            realpath(__DIR__.'/../config/configuration.php') => config_path('seeds.php')
        ], 'seed-config');
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register() {
        $this->app->singleton('smart.seed.table', function($app, $params) {
            list($data, $command) = $params;

            return new TableSeeder($data, $command);
        });

        $this->app->singleton('smart.seed.repository', function($app) {
           return new SeederDbRepository($app['db'], config('seeds.table'));
        });

        self::setCommands();
    }

    /**
     * Set commands ...
     */
    private function setCommands() {
        array_walk($this->commands, function($class, $command) {
            $this->app->bindShared($command, function() use($class, $command) {
                return new $class;
            });

            $this->commands($command);
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides() {
        return [];
    }

}
