<?php namespace LaravelSeed\Providers;

use File;
use LaravelSeed\Contracts\ProviderInterface;
use LaravelSeed\Exceptions\SeederException;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Yaml\Dumper;
use Symfony\Component\Yaml\Parser;

class YamlProvider extends AbstractProvider implements ProviderInterface {


    /**
     * Return an array of data to be parsed ...
     *
     * @param string $source
     * @throws SeederException
     * @return array|mixed
     */
    public function getData($source = '') {
        $path = self::getConfig()['path'];

        if( ! File::isDirectory( $path ) )
            throw new SeederException('Invalid directory path.');

        $files = [];

        if( $source ) {
            $files[] = trim(strtolower($source)) .'_' . trim(strtolower(self::getEnv())) . '.yaml' ;
        } else {
            $finder = new Finder;
            $finder->name('*_' . trim(strtolower(self::getEnv())) . '*');
            foreach ($finder->in(self::getConfig()['path']) as $file) {
                $files[] = $file->getFilename();
            }
        }

        $yaml   = new Parser;

        return array_map(function($file) use($yaml, $path) {
            $fullPath = $path . DIRECTORY_SEPARATOR . $file;

            if( File::exists($fullPath ))
                return $yaml->parse(File::get($fullPath ));
        }, $files);
    }

    /**
     * Make source file ..
     *
     * @param $source
     * @param $env
     * @param string $seeder
     * @throws SeederException
     * @return bool|mixed
     */
    public function create($source, $env, $seeder = '') {
        if( ! File::isDirectory(self::getConfig()['path']) )
            throw new SeederException('Invalid directory path.');

        if( ! File::isWritable( self::getConfig()['path'] ) )
            throw new SeederException('Path are not writable. Please chmod!');

        $source = explode(',', $source);
        $files = [];
        array_walk($source, function($name) use (&$files, $env) {
            $model = 'App\\' . ucfirst(strtolower( $name ));

            if( !self::isModelExists($model) )
                throw new SeederException('Invalid model class');

            $fileName =  trim(strtolower($name)) . '_' . trim(strtolower($env)) . '.yaml';
            $fullPath = self::getConfig()['path'] . DIRECTORY_SEPARATOR . $fileName;

            if( File::exists($fullPath))
                throw new SeederException('Model already exists.');

            File::put( $fullPath, self::toYaml([
                    'class'  => ucfirst($name),
                    'source' => self::toYaml(
                        self::getFieldsTable($model), 1
                    )
                ], 1
            ));

            $files[] = $fileName;
        });

        return $files;
    }

    /**
     * To Yaml converter ...
     *
     * @param array $data
     * @param int $mode
     * @return string
     */
    private function toYaml(array $data,  $mode = 1) {
        $dumper = new Dumper;

        return $dumper->dump($data, $mode);
    }

    /**
     * Check if model exists ..
     *
     * @param $name
     * @return bool
     */
    private function isModelExists($name) {
        if( ! class_exists($name) )
            return false;

        return true;

    }
}
