<?php namespace LaravelSeed\Providers;

use File;
use LaravelSeed\Exceptions\SeederException;

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
     * Get current provider extension ...
     *
     * @return mixed
     */
    public function getExtension() {
        if( $this->ext )
            return $this->ext;
    }

}
