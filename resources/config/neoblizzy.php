<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Laravel Battle.net Api configuration
    |--------------------------------------------------------------------------
    */

    'enable_cache' => env('BATTLENET_ENABLE_CACHE', true),
    'cache_timeout' => env('BATTLENET_CACHE_TIMEOUT', 120),

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
    'api_sc2_redirect_uri' => env('BATTLENET_SC2_REDIRECT_URI', ''),
    'api_wow_redirect_uri' => env('BATTLENET_WOW_REDIRECT_URI', ''),

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
