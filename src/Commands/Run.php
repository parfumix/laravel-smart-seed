<?php namespace LaravelSeed\Commands;

use App;
use LaravelSeed\Contracts\ProviderInterface;
use LaravelSeed\Exceptions\SeederException;
use LaravelSeed\ProviderFactory;
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

            $env        = self::detectEnvironment();
            $source     = $this->argument('source');
            $seeded     = getSeeded( $env );
            $collection = collect([]);

            $provider = ProviderFactory::factory($source, $env);

            if( is_array($provider) && !empty($provider['run'])  ) {
                $closure = $provider['run'];

                if( ! self::isClosure($closure))
                    throw new SeederException('Invalid closure declared to config file');

               $collection = $closure( $source, $seeded, $env, $this );

            } elseif( $provider instanceof ProviderInterface ) {
                $collection =  $provider->getData($source, $seeded);
            }

            if( $collection->isEmpty() )
                throw new SeederException('No seeds data!');

            App::make('smart.seed.table', [$this])->seed($collection, $env);

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
