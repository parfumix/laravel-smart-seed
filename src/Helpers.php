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

    function getFilesFromPathByEnv($path, $env = '') {
        $finder = new Symfony\Component\Finder\Finder;
        $files  = [];
        $finder->name('*_' . $env . '*');
        foreach ($finder->in($path) as $file) {
            $files[] = $file->getFilename();
        }

        return $files;
    }
}