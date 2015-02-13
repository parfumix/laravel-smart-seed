<?php namespace LaravelSeed\Commands;

use App;
use Closure;
use Illuminate\Console\Command;
use LaravelSeed\Exceptions\SeederException;

abstract class AbstractCommand extends Command {

    public function fire() {
        if(! is_array(config('seeds')))
            throw new SeederException('Not found configuration file. Please use vendor:publish to publish config file!');
    }

    /**
     * Detect environment ..
     *
     * @return array|string
     */
    protected function detectEnvironment() {
        if( $env = $this->option('env') )
            return $env;

        return App::environment();
    }

    /**
     * Notify user recent created files .
     *
     * @param array $files
     * @param $operation
     */
    protected function notifySources(array $files, $operation) {
        array_walk($files, function($file) use($operation) {
            $this->info(sprintf('File "%s" %s successfully!', $file, $operation));
        });
    }

    /**
     * Check if current $var is Closure type ..
     *
     * @param $var
     * @return bool
     */
    protected static function isClosure($var) {
        if( is_object( $var ) && ($var instanceof Closure) )
            return true;

        return false;
    }
}