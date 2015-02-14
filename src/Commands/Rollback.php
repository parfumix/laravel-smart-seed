<?php namespace LaravelSeed\Commands;

use App;
use LaravelSeed\Exceptions\SeederException;

class Rollback extends AbstractCommand {

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'smart:rollback';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Rollback last added seeder.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire() {
        try {
            parent::fire();

            $repository = app('smart.seed.repository');
            $env        = self::detectEnvironment();

            $lastBatch     = $repository->getLastBatch( $env );
            $rollbackSeeds = $repository->getSeeds($env, $lastBatch) ;

            if(! count($rollbackSeeds))
                throw new SeederException('No seeds to rollback!');

            App::make('smart.seed.table', [$this])->rollback($rollbackSeeds);

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
        return [];
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions() {
        return [];
    }


}
