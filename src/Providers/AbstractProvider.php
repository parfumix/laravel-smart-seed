<?php namespace LaravelSeed\Providers;

class AbstractProvider {

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
}
