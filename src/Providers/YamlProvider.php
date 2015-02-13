<?php namespace LaravelSeed\Providers;

use File;
use Illuminate\Console\Command;
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
        $path = self::getConfig('path');
        $files = [];

        if( $source ) {
            $files[] = trim(strtolower($source)) .'_' . self::getEnv() . '.yaml' ;
        } else {
            $files   = self::getFiles( $path );
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
     * @param Command $command
     * @throws SeederException
     * @return bool|mixed
     */
    public function create(Command $command) {
        $path = self::getConfig('path');

        if( ! File::isWritable( $path  ) )
            throw new SeederException('Path are not writable. Please chmod!');

        $source = explode(',', self::getSource());

        array_walk($source, function($name) use($path, $command) {
            $model = 'App\\' . ucfirst(strtolower( $name ));

            if( !self::isModelExists($model) ) {
                $command->error(sprintf('Model %s not exists. Skipped!', $model));
                return false;
            }

            $fileName = trim(strtolower($name)) . '_' . self::getEnv() . '.yaml';
            $fullPath = $path . DIRECTORY_SEPARATOR . $fileName;

            if( File::exists($fullPath)) {
                $command->error(sprintf('Model %s already exists. Skipped!', $fileName));
                return false;
            }

            File::put( $fullPath, self::toYaml([
                    'class'  => ucfirst($name),
                    'source' => self::toYaml(
                        self::getFieldsTable($model), 1
                    )
                ], 1
            ));

            $command->info(sprintf('File %s created successfully!', $fileName));
        });
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
