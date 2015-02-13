<?php namespace LaravelSeed\Providers;

class AbstractProvider {

    /**
     * @var array
     */
    private $config;

    /**
     * @var string
     */
    private $source;

    /**
     * @var
     */
    private $env;

    /**
     * @param array $config
     * @param string $source
     * @param $env
     */
    public function __construct(array $config, $source = '', $env) {

        $this->config = $config;
        $this->source = $source;
        $this->env = $env;
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
        return $this->env;
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
        return $this->getSource();
    }
}
