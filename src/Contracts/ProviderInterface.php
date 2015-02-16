<?php namespace LaravelSeed\Contracts;

use Illuminate\Console\Command;
use Illuminate\Support\Collection;

interface ProviderInterface {

    /**
     * Get an array of data ...
     *
     * @param string $source
     * @param Collection $seeded
     * @return mixed
     */
    public function getData($source = '', Collection $seeded);

    /**
     * Make an file source ..
     *
     * @param Command $command
     * @return mixed
     */
    public function create(Command $command);
}
