<?php namespace LaravelSeed;

class ProviderFactory {

    /**
     * Get instance of provider ..
     *
     * @param $source
     * @param $env
     * @return mixed
     */
    public static function factory($source, $env) {
        $default = config('seeds.default');
        $config  = config('seeds.providers.' . trim(strtolower($default)));

        if( !isset($config['class']) )
            return $config;

        return new $config['class']($config, $source, $env);
    }
}