<?php namespace LaravelSeed\Repositories;

use Illuminate\Database\ConnectionResolverInterface;
use Illuminate\Database\Schema\Blueprint;
use LaravelSeed\Contracts\RepositoryInterface;
use LaravelSeed\Exceptions\SeederException;

class SeederDbRepository implements RepositoryInterface {

    /**
     * @var ConnectionResolverInterface
     */
    private $manager;
    /**
     * @var
     */
    private $table;

    /**
     * @param ConnectionResolverInterface $manager
     */
    public function __construct(ConnectionResolverInterface $manager, $table) {

        $this->manager = $manager;
        $this->table = $table;
    }

    /**
     * Return an connection by alias ..
     *
     * @param $alias
     * @return mixed|void
     */
    public function connection($alias = '') {
        return self::getManager()->connection($alias);
    }

    /**
     * Check if seeds table already exists ..
     */
    public function isTableExists() {
        return $this->connection()->getSchemaBuilder()->hasTable(self::getDefaultTable());
    }

    /**
     * Migrate seeds table ..
     */
    public function migrateTable() {
        if( self::isTableExists() )
            throw new SeederException('Table already exists!');

        $this->connection()->getSchemaBuilder()->create(self::getDefaultTable(), function(Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('hash', 255);
            $table->string('env');
            $table->integer('batch');
            $table->index('id');
        });

        return true;
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

    /**
     * Get default table .
     *
     * @return mixed
     */
    private function getDefaultTable() {
        return $this->table;
    }

    /**
     * Return manager instance ..
     *
     * @return ConnectionResolverInterface
     */
    private function getManager() {
        return $this->manager;
    }

}