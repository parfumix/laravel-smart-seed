<?php namespace LaravelSeed\Providers;

use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Support\Facades\File;
use LaravelSeed\Contracts\ProviderInterface;
use LaravelSeed\Exceptions\SeederProviderException;

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
     * @param $model
     * @param string $seeder
     * @return bool|mixed
     */
    public function makeSource($model, $seeder = '') {

    }
}
