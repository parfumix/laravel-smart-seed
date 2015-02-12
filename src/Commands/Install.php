<?php namespace LaravelSeed\Commands;

use Illuminate\Console\Command;

class Install extends Command {

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'smart:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install smart seeder.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire() {

    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments() {
        return [];
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions() {
        return [];
    }


}
