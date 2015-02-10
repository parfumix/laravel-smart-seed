<?php namespace LaravelSeed;

use LaravelSeed\Exceptions\SeederException;

class SeederFactory {

    /**
     * Return an provider instance ..
     *
     * @param $alias
     * @throws SeederException
     */
    public static function factory($alias) {
        if(! in_array($alias, array_keys(config('seeds.providers'))))
            throw new SeederException('Invalid provider selected!');

        $config = config('seeds.providers.' . trim(strtolower($alias)));

        if( !$config['class'] )
            throw new SeederException('Invalid provider class. Please provide one!');

        return new $config['class']($config);
    }

}