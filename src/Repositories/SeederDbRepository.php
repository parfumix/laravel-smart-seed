<?php namespace LaravelSeed\Repositories;

use Illuminate\Database\ConnectionResolverInterface;
use LaravelSeed\Contracts\RepositoryInterface;
use LaravelSeed\Exceptions\SeederException;

class SeederDbRepository implements RepositoryInterface {

    /**
     * @var ConnectionResolverInterface
     */
    private $manager;

    public function __construct(ConnectionResolverInterface $manager) {

        $this->manager = $manager;
    }

    /**
     * Return an connection by alias ..
     *
     * @param $alias
     * @return mixed|void
     */
    public function connection($alias = '') {

    }

    /**
     * Check if seeds table already exists ..
     */
    public function isTableExists() {
        return false;
    }

    /**
     * Migrate seeds table ..
     */
    public function migrateTable() {
        if( self::isTableExists() )
            throw new SeederException('Table already exists!');
    }

    /**
     * Remove seeds table ..
     */
    public function rollBackTable() {
        if(! self::isTableExists())
            return true;
    }

    /**
     * Get last batch number by _ENV
     *
     * @param $env
     */
    public function getLastBatch($env) {

    }

    /**
     * Get next batch number ..
     *
     * @param $env
     */
    public function getNextBatch($env) {
        return self::getLastBatch($env) + 1;
    }

    /**
     * Get all seeds by the env ..
     *
     * @param $env
     */
    public function getSeeds($env) {

    }

}