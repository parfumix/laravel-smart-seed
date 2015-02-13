<?php namespace LaravelSeed\Contracts;


use Illuminate\Console\Command;

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
     * @param Command $command
     * @return mixed
     */
    public function create(Command $command);
}
