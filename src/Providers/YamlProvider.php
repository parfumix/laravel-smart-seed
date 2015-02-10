<?php namespace LaravelSeed\Providers;

use File;
use LaravelSeed\Contracts\ProviderInterface;
use LaravelSeed\Exceptions\SeederException;
use LaravelSeed\Exceptions\SeederProviderException;
use Symfony\Component\Yaml\Dumper;

class YamlProvider implements ProviderInterface {

    /**
     * @var array
     */
    private $config;

    public function __construct(array $config) {
        $this->config = $config;
    }

    /**
     * Return an array of data to be parsed ...
     *
     * @return array|mixed
     */
    public function getData() {
        return ['testdata' => 'testdata'];
    }

    /**
     * Make source file ..
     *
     * @param $model
     * @param string $seeder
     * @throws SeederException
     * @return bool|mixed
     */
    public function makeSource($model, $seeder = '') {
        if( ! File::isDirectory($this->config['path']) )
            throw new SeederException('Invalid directory path.');

        if( ! File::isWritable( $this->config['path'] ) )
            throw new SeederException('Path are not writable. Please chmod!');

        $fileName =  trim(strtolower($model)) . '.yaml';
        $fullPath = $this->config['path'] . DIRECTORY_SEPARATOR . $fileName;

        if( File::exists($fullPath))
            throw new SeederException('Model already exists.');

        File::put( $fullPath, self::toYaml(
            [
                'class'  => ucfirst($model),
                'source' => array(
                    
                )
            ], 1
        ));

        return $fileName;

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
}
