<?php namespace LaravelSeed;

use DB;
use Illuminate\Console\Command;
use Symfony\Component\Finder\Finder;

class TableSeeder {

    /**
     * @var Command
     */
    private $command;

    /**
     * @var array
     */
    private $data;

    /**
     * @param array $data
     * @param Command $command
     */
    public function __construct(array $data, Command $command) {
        $this->command = $command;
        $this->data = $data;
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
     * @return array
     */
    public function seed($env) {
        if(! $data = $this->data) {
            self::getCommand()->info('No have seeds!');
            return false;
        }

        $seedRepository = app('smart.seed.repository');

        DB::transaction(function() use($seedRepository, $env, $data) {
            array_walk($data, function($seed) use($seedRepository, $env) {
                $class = $seed['class'];

                array_walk($seed['source'], function($source) use($class) {
                    $classname = 'App\\' . $class;
                    $classname::create($source);
                });

                $seedRepository->addSeed(strtolower($class) . '_' . $env, 'hash', $env);

                self::getCommand()->info(sprintf('Class %s seeded successfully!', $class));
            });
        });
    }
}