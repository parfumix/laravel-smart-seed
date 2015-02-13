<?php namespace LaravelSeed;

use LaravelSeed\Contracts\ProviderInterface;

class TableSeeder {


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
    }



    protected function setProvider(ProviderInterface $provider) {

    }

    protected function getProvider() {

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