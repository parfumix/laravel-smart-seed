<?php namespace LaravelSeed\Contracts;

interface ProviderInterface {

    /**
     * Get an array of data ...
     *
     * @return mixed
     */
    public function getData();

    /**
     * Make an file source ..
     *
     * @param $source
     * @param string $seeder
     * @return mixed
     */
    public function create($source, $seeder = '');
}
