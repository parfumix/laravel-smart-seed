<?php namespace LaravelSeed;

use Illuminate\Console\Command;
use LaravelSeed\Commands\AbstractCommand;
use LaravelSeed\Contracts\ProviderInterface;
use LaravelSeed\Exceptions\SeederException;
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
     * @param array $seeds
     * @return array
     */
    public function seed(array $seeds) {
        array_walk($seeds, function($seed) {
            $class = $seed['class'];

            array_walk($seed['source'], function($source) use($class) {
                $classname = 'App\\' . $class;
                $classname::create($source);
            });

            self::getCommand()->info(sprintf('Class %s seeded successfully!', $class));
        });
    }
}