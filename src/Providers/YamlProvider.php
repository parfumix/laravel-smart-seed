<?php namespace LaravelSeed\Providers;

use LaravelSeed\Contracts\ProviderInterface;

class YamlProvider implements ProviderInterface {

    /**
     * Return an array of data to be parsed ...
     *
     * @return array|mixed
     */
    public function getData() {
        return [];
    }
}
