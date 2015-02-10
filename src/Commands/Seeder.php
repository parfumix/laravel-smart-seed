<?php namespace LaravelSeed\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class Seeder extends Command {

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'yaml:seeder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Seed an database from yaml.';

    /**
     * Create a new command instance.
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire() {
        return $this->info('This is seeder info');
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments() {
        return [
            ['operation', InputArgument::REQUIRED, 'An operation to run.', 'run'],
        ];
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions() {
        return [
            ['model', null, InputOption::VALUE_REQUIRED, 'Eloquent class model.', null],
            ['class', null, InputOption::VALUE_OPTIONAL, 'An default DbClassSeeder.', null],
            ['path', null, InputOption::VALUE_OPTIONAL, 'An custom path to create yaml seeders.', null],
        ];
    }

}
