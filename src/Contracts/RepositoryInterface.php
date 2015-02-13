<?php namespace LaravelSeed\Contracts;

interface RepositoryInterface {

    /**
     * Get connection by alias ..
     *
     * @param $alias
     * @return mixed
     */
    public function connection($alias);
}
