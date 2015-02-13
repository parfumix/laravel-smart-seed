<?php namespace LaravelSeed;

use LaravelSeed\Contracts\ProviderInterface;

class TableSeeder {

    /**
     * @var
     */
    protected $provider;

    /**
     * @var string
     */
    private $source;

    /**
     * @var
     */
    private $env;

    public function __construct($source = '', $env) {
        $this->source = $source;
        $this->env = $env;

        self::setProvider( app(Laravel5SeedServiceProvider::IOC_ALIAS)->factory(config('seeds.default')) );
    }


    /**
     * Set provider instance ..
     *
     * @param ProviderInterface $provider
     */
    protected function setProvider(ProviderInterface $provider) {
        if (!empty($this->provider)) {
            $this->provider = $provider;
        }
    }

    /**
     * Get provider instance ..
     *
     * @return mixed
     */
    protected function getProvider() {
        return $this->provider;
    }

    /**
     * Seed data ...
     *
     * @param array $seeds
     * @return array
     */
    public function seed(array $seeds) {
        $files = [];
        array_walk($seeds, function($seed) use(&$files) {
            $class = $seed['class'];

            array_walk($seed['source'], function($source) use($class) {
                $classname = 'App\\' . $class;
                $classname::create($source);
            });

            $files[] = $class;
        });

        return $files;
    }
}