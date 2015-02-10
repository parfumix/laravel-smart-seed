<?php

use LaravelSeed\Providers\YamlProvider;

return array(

    /*
      |--------------------------------------------------------------------------
      | DEFAULT PATH WILL BE STORED SEED FILES
      |--------------------------------------------------------------------------
      |
      */

    'path' => '',


    'providers' => array(

        'yaml' => array(
            'class' => YamlProvider::class
        )
    )
);