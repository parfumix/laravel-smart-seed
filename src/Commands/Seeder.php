<?php namespace LaravelSeed\Commands;

use Closure;
use Illuminate\Console\Command;
use LaravelSeed\Contracts\ProviderInterface;
use LaravelSeed\Exceptions\SeederException;
use LaravelSeed\Laravel5SeedServiceProvider;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class Seeder extends Command {

    private $availableArgs = ['run', 'make'];

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

            if( $this->argument('operation') != 'run' )
                if( ! class_exists('App\\' . ucfirst(strtolower($this->option('model')))) )
                    throw new SeederException('Invalid model class');

            // by default for the moment we will using only yaml provider to parse data from yaml files ..
            $provider = app(Laravel5SeedServiceProvider::IOC_ALIAS)->factory('yaml');

            if( $this->argument('operation') == 'run' ) {

                // need to be run all of the registered seeds ...

            } elseif( $this->argument('operation') == 'make' ) {
                if( $provider instanceof ProviderInterface ) {

                    if( $file = $provider->makeSource( $this->option('model'), $this->option('class') ) )
                        $this->info(sprintf('File "%s" created successfully!', $file));

                } else {
                    if( $closure = $provider['source'] ) {
                        if( ! self::isClosure($closure))
                            throw new SeederException('Invalid closure declared to config file');

                        if( $file = $closure( $this->option('model'), $this->option('class') ) )
                            $this->info(sprintf('File "%s" created successfully!', $file));
                    }
                }
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
}
