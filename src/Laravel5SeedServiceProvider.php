<?php namespace LaravelSeed;

use Illuminate\Support\ServiceProvider;

class Laravel5SeedServiceProvider extends ServiceProvider {

    const IOC_ALIAS = __NAMESPACE__;

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
        ], 'config');
    }



    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register() {
        $this->app->singleton(self::IOC_ALIAS, function() {
            return new SeederFactory;
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
