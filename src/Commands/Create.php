<?php namespace LaravelSeed\Commands;

use App;
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

            $env    = self::detectEnvironment();
            $source = $this->argument('source');

            $provider = App::make('smart.provider.factory', [ $source, $env ]);

            if( is_array($provider) && !empty($provider['create'])  ) {
                $closure = $provider['create'];

                if( ! self::isClosure($closure))
                    throw new SeederException('Invalid closure declared to config file');

                $closure( $this->argument('source'), $env, $this );
            } elseif( $provider instanceof ProviderInterface ) {

                $provider->create($this);
            }
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
