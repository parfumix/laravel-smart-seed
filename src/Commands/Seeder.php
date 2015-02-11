<?php namespace LaravelSeed\Commands;

use Closure;
use Illuminate\Console\Command;
use LaravelSeed\Contracts\ProviderInterface;
use LaravelSeed\Exceptions\SeederException;
use LaravelSeed\Laravel5SeedServiceProvider;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class Seeder extends Command {

    private $availableArgs = ['run', 'create'];

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'db:seeder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Seed an database from specific provider source.';

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
        try {
            if( !in_array($this->argument('operation'), $this->availableArgs) )
                throw new SeederException(printf('Please provider an operation! Use follow commands: %s.', implode(', ', $this->availableArgs)));

            // by default for the moment we will using only yaml provider to parse data from yaml files ..
            $provider = app(Laravel5SeedServiceProvider::IOC_ALIAS)->factory(config('seeds.default'));


            switch( $this->argument('operation') ) {
                case 'run':
                        if( is_array($provider) && !empty($provider['run']) ) {
                            $closure = $provider['run'];

                            if( ! self::isClosure($closure))
                                throw new SeederException('Invalid closure declared to config file');

                            $data = $closure();
                        } elseif( $provider instanceof ProviderInterface ) {
                            $data = $provider->getData();
                        }

                    break;

                case 'create':
                        if( is_array($provider) && !empty($provider['source'])  ) {
                            $closure = $provider['source'];
                            if( ! self::isClosure($closure))
                                throw new SeederException('Invalid closure declared to config file');

                            if( $files = $closure( $this->argument('source'), $this->option('class') ) )
                                self::notifySources($files);

                        } elseif( $provider instanceof ProviderInterface ) {

                            if( $files = $provider->create( $this->argument('source'), $this->option('class') ) )
                                self::notifySources($files);

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
            ['source',    InputArgument::OPTIONAL, 'An source name.'],
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
     */
    private function notifySources(array $files) {
        array_walk($files, function($file) {
            $this->info(sprintf('File "%s" created successfully!', $file));
        });
    }

}
