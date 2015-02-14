<?php

/**
 * Convert data from array to yaml format .
 */
if( !function_exists('func_name') ) {

    function arrayToYaml(array $array, $mode = 1) {
        $dumper = new Symfony\Component\Yaml\Dumper;

        return $dumper->dump($array, $mode);
    }
}