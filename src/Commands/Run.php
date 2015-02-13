<?php namespace LaravelSeed\Commands;

use LaravelSeed\Contracts\ProviderInterface;
use LaravelSeed\Exceptions\SeederException;
use LaravelSeed\Laravel5SeedServiceProvider as Provider;
use LaravelSeed\TableSeeder;
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

            if(! $this->argument('source'))
                throw new SeederException('Invalid source!');

            $env = self::detectEnvironment();

            $provider = app(Provider::IOC_ALIAS)->factory(config('seeds.default'));

            if( is_array($provider) && !empty($provider['run']) ) {
                $closure = $provider['run'];

                if( ! self::isClosure($closure))
                    throw new SeederException('Invalid closure declared to config file');

                if( $files = TableSeeder::seed($closure()) )
                    self::notifySources($files, 'seeded');

            } elseif( $provider instanceof ProviderInterface ) {
                if( $files = TableSeeder::seed($provider->getData()) )
                    self::notifySources($files, 'seeded');

            }
        } catch(SeederException $e) {
            $this->error('\n' . $e->getMessage());
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
