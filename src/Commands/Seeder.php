<?php namespace LaravelSeed\Commands;

use Closure;
use Illuminate\Console\Command;
use LaravelSeed\Contracts\ProviderInterface;
use LaravelSeed\Exceptions\SeederException;
use LaravelSeed\Laravel5SeedServiceProvider;
use LaravelSeed\TableSeeder;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class Seeder extends Command {

    private $availableArgs = ['run', 'create'];

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'smart:seed';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Seed an database from specific provider source.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire() {
        try {
            if(! is_array(config('seeds')))
                throw new SeederException('Not found configuration file. Please use vendor:publish to publish config file!');
            
            if( !in_array($this->argument('operation'), $this->availableArgs) )
                throw new SeederException(printf('Please provider an operation! Use follow commands: %s.', implode(', ', $this->availableArgs)));

            $provider = app(Laravel5SeedServiceProvider::IOC_ALIAS)->factory(config('seeds.default'));

            switch( $this->argument('operation') ) {
                case 'run':
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


                    break;

                case 'create':
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
                    break;
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
            ['operation', InputArgument::OPTIONAL, 'An operation to run. Use "create" to create an source and "run" to seed database.'],
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
            ['class', null, InputOption::VALUE_OPTIONAL, 'An default DbClassSeeder.', null],
            ['local', null, InputOption::VALUE_OPTIONAL, 'Run default seeders.', null],
        ];
    }

    /**
     * Check if current $var is Closure type ..
     *
     * @param $var
     * @return bool
     */
    private static function isClosure($var) {
        if( is_object( $var ) && ($var instanceof Closure) )
            return true;

        return false;
    }

    /**
     * Notify user recent created files .
     *
     * @param array $files
     * @param $operation
     */
    private function notifySources(array $files, $operation) {
        array_walk($files, function($file) use($operation) {
            $this->info(sprintf('File "%s" %s successfully!', $file, $operation));
        });
    }

}
