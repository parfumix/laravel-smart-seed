<?php namespace LaravelSeed\Providers;

use File;
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
     * @return array
     */
    public function getConfig() {
        return $this->config;
    }

    /**
     * Get files from specific path and $_ENV ..
     *
     * @param $path
     * @param $env
     * @return array
     */
    protected function getFiles($path, $env = '') {
        if(! $env)
            $env = self::getEnv();

        $finder = new Finder;
        $files  = [];
        $finder->name('*_' . $env . '*');
        foreach ($finder->in($path) as $file) {
            $files[] = $file->getFilename();
        }

        return $files;
    }
}
