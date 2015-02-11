<?php

use LaravelSeed\Providers\YamlProvider;

return array(

    /*
      |--------------------------------------------------------------------------
      | DEFAULT PATH WILL BE STORED SEED FILES
      |--------------------------------------------------------------------------
      |
      */
    'default' => 'yaml',

    /*
     |--------------------------------------------------------------------------
     | LIST OF PROVIDERS LISTED BELOW
     |--------------------------------------------------------------------------
     |
     */
    'providers' => array(

        'yaml' => array(
            'path'   => config_path('seeds/yaml'),
            'class'  => YamlProvider::class,
        )
    )
);
