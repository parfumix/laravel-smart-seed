<?php namespace LaravelSeed;

class TableSeeder {

    /**
     * Seed data ...
     *
     * @param array $seeds
     * @return array
     */
    public static function seed(array $seeds) {
        $files = [];
        array_walk($seeds, function($seed) use(&$files) {
            $class = $seed['class'];

            array_walk($seed['source'], function($source) use($class) {
                $classname = 'App\\' . $class;
                $classname::create($source);
            });

            $files[] = $class;
        });

        return $files;
    }
}