<?php namespace LaravelSeed\Commands;

use LaravelSeed\Contracts\ProviderInterface;
use LaravelSeed\Exceptions\SeederException;
use LaravelSeed\Laravel5SeedServiceProvider as Provider;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class Create extends AbstractCommand {

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'smart:create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create smart Eloquent seeders.';

    /**
     * Execute the console command.
     *
     * @throws SeederException
     * @return mixed
     */
    public function fire() {
        try {
            parent::fire();

            if(! $this->argument('source'))
                throw new SeederException('Invalid source!');

            $provider = app(Provider::IOC_ALIAS)->factory(config('seeds.default'));

            if( is_array($provider) && !empty($provider['create'])  ) {
                $closure = $provider['create'];

                if( ! self::isClosure($closure))
                    throw new SeederException('Invalid closure declared to config file');

                if( $files = $closure( $this->argument('source'), $this->option('class') ) )
                    self::notifySources($files, 'created');

            } elseif( $provider instanceof ProviderInterface ) {

                if( $files = $provider->create( $this->argument('source'), $this->option('class') ) )
                    self::notifySources($files, 'created');

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
            ['source',    InputArgument::OPTIONAL, 'An source Eloquent model name.'],
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
