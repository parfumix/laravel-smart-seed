<?php namespace LaravelSeed\Providers;

use File;
use Illuminate\Support\Collection;
use LaravelSeed\Exceptions\SeederException;
use Symfony\Component\Finder\Finder;

class AbstractProvider {

    /**
     * @var array
     */
    protected $config;

    /**
     * @var string
     */
    protected $source;

    /**
     * @var
     */
    protected $env;

    /**
     * @param array $config
     * @param string $source
     * @param $env
     * @throws SeederException
     */
    public function __construct(array $config, $source = '', $env) {

        $this->config = $config;
        $this->source = $source;
        $this->env = $env;

        if( ! File::isDirectory( self::getConfig()['path'] ) )
            throw new SeederException('Invalid directory path.');
    }

    /**
     * Get fields table ..
     *
     * @param $table
     * @return mixed
     */
    public static function getFieldsTable($table) {
        $modelObj = new $table;
        $fields   = app('db')
            ->connection()
            ->getSchemaBuilder()
            ->getColumnListing( $modelObj->getTable() );

        return $fields;
    }

    /**
     * @param $env
     */
    public function setEnv($env) {
        $this->env = $env;
    }

    /**
     * @return mixed
     */
    public function getEnv() {
        return trim(strtolower($this->env));
    }

    /**
     * @param $source
     */
    public function setSource($source) {
        $this->source = $source;
    }

    /**
     * @return mixed
     */
    public function getSource() {
        return $this->source;
    }

    /**
     * Get configurations..
     *
     * @param string $key
     * @return array
     */
    public function getConfig($key = '') {
        if($key)
            return $this->config[$key];

        return $this->config;
    }

    /**
     * Get diff files ...
     *
     * @param $files
     * @param Collection $seededFiles
     * @return array
     */
    protected function diffFiles($files, Collection $seededFiles = null) {
        if(! $seededFiles)
            $seededFiles = app('smart.seed.repository')->getSeeds( self::getEnv() );

        $filenameSeeded = array_map(function($file) {
            return $file->name;
        }, $seededFiles->toArray());

        $files = array_map(function($file) {
            $file = explode('.', $file);
            return $file[0];
        }, $files);

        return array_diff($files, $filenameSeeded);
    }
}
