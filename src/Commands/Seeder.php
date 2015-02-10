<?php namespace LaravelSeed\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class Seeder extends Command {

    private $availableArgs = ['run', 'make'];

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
        if( !in_array($this->argument('operation'), $this->availableArgs) )
            return $this->error(
                sprintf('Please provider an operation! Use follow commands: %s.', implode(', ', $this->availableArgs))
            );

        if( ! class_exists('App\\' . ucfirst(strtolower($this->option('model')))) )
            return $this->error('Invalid model class');



    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments() {
        return [
            ['operation', InputArgument::OPTIONAL, 'An operation to run.'],
        ];
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions() {
        return [
            ['model', null, InputOption::VALUE_OPTIONAL, 'Eloquent class model.', null],
            ['class', null, InputOption::VALUE_OPTIONAL, 'An default DbClassSeeder.', null],
            ['path', null, InputOption::VALUE_OPTIONAL, 'An custom path to create yaml seeders.', null],
        ];
    }

}
