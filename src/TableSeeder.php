<?php namespace LaravelSeed;

use DB;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class TableSeeder {

    protected $repository;

    /**
     * @var Command
     */
    private $command;

    /**
     * @var
     */
    protected $seeds;

    /**
     * @param Command $command
     */
    public function __construct(Command $command) {
        $this->command = $command;
    }

    /**
     * Get command instance ..
     *
     * @return Command
     */
    private function getCommand() {
        return $this->command;
    }



    /**
     * Seed data ...
     *
     * @param Collection $data
     * @param $env
     * @return array
     */
    public function seed(Collection $data, $env) {
        DB::transaction(function () use ($env, $data) {

            $data->each(function ($seed) use($env) {
                $class   = getFirstKeyArray($seed);
                $batch   = self::getRepository()->getNextBatch($env);
                $message = '';

                $classSeeded = str_singular($class) . '_' . $env;
                 if( self::isSeed($classSeeded, $env) ) {

                     self::updateSeed( self::getSeed( $classSeeded, $env )->id, $seed['hash'] );

                     $message = sprintf("%s was updated successfully", ucfirst(str_singular($class)));

                     self::rollbackProcessing(array_reverse(array_unique(getModelsSeed($seed))));
                 } else {

                     self::getRepository()->addSeed($classSeeded, $seed['hash'], $env, $batch);

                     $message = sprintf("%s was updated successfully", ucfirst(str_singular($class)));

                 }

                self::seedProcessing(str_singular($class), null, $seed[$class]);

                self::getCommand()->info($message);
            });
        });
    }

    /**
     * Iterate over all childrens ...
     *
     * @param $class
     * @param null $parent
     * @param array $items
     */
    public function seedProcessing($class, $parent = null, array $items) {
        $model = self::getTable($class);

        array_walk($items, function ($item) use ($model, $parent) {
            if (isset($parent->id)) {
                $item = array_merge([str_singular($parent->getTable()) . '_id' => $parent->id], $item);
            }

            $created = $model::create($item);

            if ($child = getFirstKeyArray($item))
                self::seedProcessing(str_singular($child), $created, $item[$child]);
        });
    }



    /**
     * Rollback seeds ...
     *
     * @param Collection $seeds
     */
    public function rollback(Collection $seeds) {
        DB::transaction(function () use ($seeds) {

            $seeds->map(function ($seed) {
                //#@todo ...

                DB::table(config('seeds.table'))->where('id', '=', $seed->id)->delete();

                self::getCommand()->info(sprintf('Class %s rollback successfully!', 1));
            });

        });
    }

    /**
     * Rollback models ...
     *
     * @param array $revert
     */
    private function rollbackProcessing(array $revert) {
        array_walk($revert, function($model) {
            if( isEloquentExists( str_singular($model) ) ) {
                $table =  self::getTable(str_singular($model));
                DB::table($table->getTable())->delete();
            }

            return false;
        });
    }




    /**
     * Get seed repository ..
     *
     * @return mixed
     */
    private function getRepository() {
        if ($this->repository)
            return $this->repository;

        return app('smart.seed.repository');
    }

    private function getTable($class) {
        $classname = 'App\\' . $class;
        return new $classname;
    }




    /**
     * Check if class has been seeded ..
     *
     * @param $class
     * @param $env
     * @return bool
     */
    private function isSeed($class, $env) {
        if ($seed = self::getSeed($class, $env))
            return true;

        return false;
    }

    /**
     * Get seed class by env ...
     *
     * @param $class
     * @param $env
     * @return mixed
     */
    private function getSeed($class, $env) {
        if (!isset($this->seeds[strtolower($class) . '_' . $env])) {
            if ($seed = self::getRepository()->getSeed(strtolower($class), $env))
                return $seed;

            return false;
        }

        return $this->seeds[strtolower($class) . '_' . $env];
    }

    /**
     * Update seed ...
     *
     * @param $seed
     * @param $hash
     * @return mixed
     */
    private function updateSeed($seed, $hash) {
        return self::getRepository()->updateSeed($seed, ['hash' => $hash]);
    }
}