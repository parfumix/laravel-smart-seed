<?php namespace LaravelSeed;

use DB;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;

class TableSeeder {

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
        $seedRepository = app('smart.seed.repository');
        $batch = $seedRepository->getNextBatch($env);

        DB::transaction(function() use($seedRepository, $env, $data, $batch) {

            $data->each(function($seed) use($seedRepository, $env, $batch) {

                $class = $seed['class'];

                $isSeeded = false;
                $name     =  strtolower($class) . '_' . strtolower($env);


                $classname = 'App\\' . $class;
                $obj       = new $classname;
                $table     = $obj->getTable();

                if( $seedObj = $seedRepository->getSeed( $name, $env ) ) {
                    $isSeeded = true;

                    $seedRepository->updateSeed($seedObj->id, [
                     'hash' => $seed['hash']
                    ]);

                    DB::table( $table )->delete();
                }

                array_walk($seed['source'], function($source) use($class, $isSeeded, $table, $classname) {
                    $classname::create($source);
                });

                if(! $isSeeded)
                    $seedRepository->addSeed(strtolower($class) . '_' . $env,  $seed['hash'], $env, $batch);

                $message = sprintf('Class %s seeded successfully!', $class);
                if( $isSeeded )
                    $message = sprintf('Class %s updated successfully!', $class);

                self::getCommand()->info($message);
            });

        });
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

               if(! isEloquentExists($classname)) {
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
}