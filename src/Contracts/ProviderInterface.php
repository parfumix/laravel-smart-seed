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
     * @return mixed
     */
    public function makeFile();
}
