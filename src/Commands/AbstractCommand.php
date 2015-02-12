<?php namespace LaravelSeed\Commands;

use Closure;
use Illuminate\Console\Command;

abstract class AbstractCommand extends Command {

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