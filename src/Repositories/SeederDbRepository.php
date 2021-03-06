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
     * @param $table
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

        $this->connection()->getSchemaBuilder()->drop(self::getDefaultTable());

        return true;
    }

    /**
     * Get last batch number by _ENV
     *
     * @param $env
     * @return static
     */
    public function getLastBatch($env) {
        $batch = collect(
            $this->connection()->table(self::getDefaultTable())
                ->where('env', '=', $env)
                ->orderBy('batch', 'desc')
                ->get(['batch'])
        )->first();

        if( isset($batch) )
            return $batch->batch;

        return 0;
    }

    /**
     * Get next batch number ..
     *
     * @param $env
     * @return \LaravelSeed\Repositories\SeederDbRepository
     */
    public function getNextBatch($env) {
        return self::getLastBatch($env) + 1;
    }

    /**
     * Get all seeds by the env ..
     *
     * @param $env
     * @param string $batch
     * @return static
     */
    public function getSeeds($env, $batch = '') {
        $query =  $this->connection()->table(self::getDefaultTable())
            ->where('env', '=', $env);

        if( $batch )
            $query->where('batch', '=', $batch);

        return collect($query ->get(['*']));
    }

    /**
     * Get seed by $name and $env ...
     *
     * @param $name
     * @param $env
     * @return mixed|null
     */
    public function getSeed($name, $env) {
        return collect(
            $this->connection()->table( self::getDefaultTable() )
                ->where('env', '=', $env)
                ->where('name', '=', $name)
                ->get(['*'])
        )->first();
    }

    /**
     * Insert an seed ..
     *
     * @param $name
     * @param $hash
     * @param $env
     * @param $batch
     * @return
     */
    public function addSeed($name, $hash, $env, $batch) {
        return $this->connection()->table(self::getDefaultTable())
           ->insert([
               'name'   => $name,
               'hash'   => $hash,
               'env'    => $env,
               'batch'  => $batch,
           ]);
    }

    /**
     * Update seed .
     *
     * @param $id
     * @param $params
     * @return mixed
     */
    public function updateSeed( $id, $params ) {
        return $this->connection()->table( self::getDefaultTable() )
            ->where('id', '=', $id)
            ->update($params);
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