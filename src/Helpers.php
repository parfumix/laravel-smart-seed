<?php

/**
 * Convert data from array to yaml format .
 */
if( !function_exists('arrayToYaml') ) {

    function arrayToYaml(array $array, $mode = 1) {
        $dumper = new Symfony\Component\Yaml\Dumper;

        return $dumper->dump($array, $mode);
    }
}

/**
 * Get files from specific path by env ..
 */
if( !function_exists('getFilesFromPathByEnv')) {

    function getFilesFromPathByEnv(\LaravelSeed\Contracts\ProviderInterface $provider) {
        $finder = new Symfony\Component\Finder\Finder;
        $files  = [];
        $finder->name("/\_".$provider->getExtension()."\.(\w{1,4})$/i");
        foreach ($finder->in($provider->getConfig('path')) as $file) {
            $files[] = $file->getPath() . '/' .$file->getFilename();
        }

        return $files;
    }
}

/**
 * Check if Eloquent model exists ..
 */
if( !function_exists('isEloquentExists')) {

    function isEloquentExists($class) {
        if( !preg_match('/^app/i', $class) )
            $class = 'App\\' . $class;

        if(! class_exists($class))
            return false;

        return true;
    }
}

/**
 * Get diff files from seeded and local ..
 */
if( !function_exists('getDiffFiles')) {

    function getDiffFiles(array $files, \Illuminate\Support\Collection $seeded, \LaravelSeed\Contracts\ProviderInterface $provider) {
        $edited = [];
        array_map(function($seed) use($files, &$edited, $provider) {
            $fullPath = $provider->getFullPath( $seed->name );

            if(! in_array($fullPath, $files))
                return false;

            $key       = array_search($fullPath, $files);
            $filemtime = filemtime($files[$key]);

            if( $filemtime > $seed->hash )
                $edited[] = $fullPath;

            return false;
        }, $seeded->toArray());

        $diff = [];
        array_walk($files, function($file) use($seeded, &$diff) {
            $filename = pathinfo($file)['filename'];

            if(! $seeded->contains('name', $filename))
                $diff[] = $file;
        });

        return array_merge($diff, $edited);
    }
}

/**
 * Get columns from specific table ..
 */
if( !function_exists('getTableSchema')) {

    function getTableSchema($table) {
        if( !is_object($table))
            $table = new $table;

        $fields = \DB::getSchemaBuilder()
            ->getColumnListing( $table->getTable() );

        return $fields;
    }
}

/**
 * Get seeded files by env ..
 *
 */
if(! function_exists('getSeeded')) {

    function getSeeded($env) {
        return app('smart.seed.repository')->getSeeds( $env );
    }
}

/**
 * Get full path by source ...
 */
if(! function_exists('getFullPathSource')) {

    function getFullPathSource($source, \LaravelSeed\Contracts\ProviderInterface $provider) {
        $path   = $provider->getConfig('path');
        $source = pathinfo($source)['filename'];
        $env    = $provider->getEnv();

        if( !preg_match("/\\_".$env."/i", $source) )
            $path .= DIRECTORY_SEPARATOR . $source . '_' . $env;

        $path .= '.' . $provider->getExtension();

        return $path;
    }
}