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
     * @param array $data
     * @param $env
     * @return array
     */
    public function seed(array $data, $env) {
        $seedRepository = app('smart.seed.repository');
        $batch = $seedRepository->getNextBatch($env);

        DB::transaction(function() use($seedRepository, $env, $data, $batch) {
            array_walk($data, function($seed) use($seedRepository, $env, $batch) {
                $class = $seed['class'];

                array_walk($seed['source'], function($source) use($class) {
                    $classname = 'App\\' . $class;
                    $classname::create($source);
                });

                $seedRepository->addSeed(strtolower($class) . '_' . $env, 'hash', $env, $batch);

                self::getCommand()->info(sprintf('Class %s seeded successfully!', $class));
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