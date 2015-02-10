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
     * @param $model
     * @param string $seeder
     * @return mixed
     */
    public function makeSource($model, $seeder = '');
}
