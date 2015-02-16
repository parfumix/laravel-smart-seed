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
     * @param Command $command
     */
    public function __construct( Command $command) {
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
        $batch = self::getRepository()->getNextBatch($env);

        DB::transaction(function() use($env, $data, $batch) {

            $data->each(function($seed) {
                self::process($seed['class'], $seed['child'] ?: null, null, $seed['source']);
            });
        });
    }


    public function process($class, $child = null, $parent = null, array $items) {
        $model = self::getTable($class);

        array_walk($items, function($item) use($model, $child, $parent) {
            $toInsert = $item;

            if( isset($parent->id) )
                $toInsert += [strtolower(str_singular($parent->getTable())) .'_id' => $parent->id];

            $parentObj = $model::create($toInsert);

            if( isset($item['items']) )
                self::process( $child, isset($item['child']) ? $item['child'] : null, $parentObj, $item['items']);
        });


       /* $isSeeded = false;


        if( $seedObj = self::getRepository()->getSeed( strtolower($class), $env ) ) {
            $isSeeded = true;

            self::getRepository()->updateSeed($seedObj->id, [
                'hash' => $seed['hash']
            ]);

            DB::table( $table )->delete();
        }



        if(! $isSeeded)
            self::getRepository()->addSeed(strtolower($class),  $seed['hash'], $env, $batch);

        $message = sprintf('Class %s seeded successfully!', $class);
        if( $isSeeded )
            $message = sprintf('Class %s updated successfully!', $class);

        self::getCommand()->info($message);*/
    }

    /**
     * Rollback seeds ...
     *
     * @param Collection $seeds
     */
    public function rollback(Collection $seeds) {
        DB::transaction(function() use($seeds) {
           $seeds->map(function($seed) {
               $class     = current(explode('_', $seed->name));
               $classname = 'App\\' . ucfirst($class);

               if( !isEloquentExists($classname)) {
                   self::getCommand()->error(sprintf('Class %s do not exists. Skipped!', $classname));
                   return false;
               }

               $obj = new $classname;
               DB::table( $obj->getTable() )->delete();
               DB::table(config('seeds.table'))->where('id', '=', $seed->id)->delete();

               self::getCommand()->info(sprintf('Class %s rollback successfully!', $classname));
           });
        });
    }

    /**
     * Get seed repository ..
     *
     * @return mixed
     */
    private function getRepository() {
        if( $this->repository )
            return $this->repository;

        return app('smart.seed.repository');
    }

    private function getTable($class) {
        $classname = 'App\\' . $class;
        return new $classname;
    }
}