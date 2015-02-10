<?php namespace LaravelSeed\Providers;

use LaravelSeed\Contracts\ProviderInterface;

class YamlProvider implements ProviderInterface {

    /**
     * @var array
     */
    private $config;

    public function __construct(array $config) {
        $this->config = $config;
    }

    /**
     * Return an array of data to be parsed ...
     *
     * @return array|mixed
     */
    public function getData() {
        return ['testdata' => 'testdata'];
    }

    /**
     * Make source file ..
     *
     * @return bool|mixed
     */
    public function makeFile() {
        return true;
    }
}
