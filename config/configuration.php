<?php

use LaravelSeed\Providers\YamlProvider;

return array(

    /*
      |--------------------------------------------------------------------------
      | DEFAULT PATH WILL BE STORED SEED FILES
      |--------------------------------------------------------------------------
      |
      */

    'providers' => array(

        'yaml' => array(
            'path'   => config_path('wl/seeds/yaml'),
            'class'  => YamlProvider::class,
            'run' => function( ) {
                // logic
            }
        )
    )
);