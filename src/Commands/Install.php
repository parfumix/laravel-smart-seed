<?php namespace LaravelSeed\Commands;

use Illuminate\Support\Facades\Artisan;
use LaravelSeed\Exceptions\SeederException;

class Install extends AbstractCommand {

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'smart:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install smart seeder.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire() {
        try {
            $repository = app('smart.seed.repository');

            Artisan::call('vendor:publish', ['--tag' => 'seed-config']);

            $this->info('Publishing complete!');

            if( $repository->isTableExists() )
                throw new SeederException('Table already has been migrated!');

            if( $repository->migrateTable() )
                $this->info('Table has been migrated successfully!');

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
