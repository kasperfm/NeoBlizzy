<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Laravel Battle.net Api configuration
    |--------------------------------------------------------------------------
    */

    'enable_cache' => false,
    'cache_timeout' => 60,

    /*
    |--------------------------------------------------------------------------
    | Battle.net API credentials
    |--------------------------------------------------------------------------
    |
    | Before you be able to make requests to the Battle.net API, you need to provide your API key.
    | If you don't have an API key, go to https://dev.battle.net/docs to get one.
    |
    */

    'api_key' => env('BATTLENET_API_KEY', ''),
    'secret_key' => env('BATTLENET_API_SECRET', ''),

    /*
    |--------------------------------------------------------------------------
    | Battle.net Locale
    |--------------------------------------------------------------------------
    |
    | Define what locale to use for the Battle.net API response.
    | For examples: en_US | en_GB | fr_FR | de_DE | ru_RU
    |
    */
    'locale' => env('BATTLENET_LOCALE', 'en_GB')

];
