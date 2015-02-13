<?php namespace LaravelSeed\Commands;

use App;
use LaravelSeed\Exceptions\SeederException;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class Run extends AbstractCommand {

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'smart:run';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run recent create seeds.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire() {
        try {
            parent::fire();

            App::make('smart.seed.table', [$this->argument('source'), self::detectEnvironment()]);

        } catch(SeederException $e) {
            $this->error($e->getMessage());
        }
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments() {
        return [
            ['source',    InputArgument::OPTIONAL, 'An source Eloquent model name to run.'],
        ];
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions() {
        return [
            ['env', null, InputOption::VALUE_OPTIONAL, 'The environment the command should run under.', null],
        ];
    }


}
