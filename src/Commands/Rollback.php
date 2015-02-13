<?php namespace LaravelSeed\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use LaravelSeed\Exceptions\SeederException;

class Rollback extends Command {

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
