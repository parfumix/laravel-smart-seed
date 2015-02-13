<?php namespace LaravelSeed\Contracts;

interface ProviderInterface {

    /**
     * Get an array of data ...
     *
     * @param string $source
     * @return mixed
     */
    public function getData($source = '');

    /**
     * Make an file source ..
     *
     * @param $source
     * @param $env
     * @param string $seeder
     * @return mixed
     */
    public function create($source, $env, $seeder = '');
}
