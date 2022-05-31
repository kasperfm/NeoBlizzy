<?php
namespace KasperFM\NeoBlizzy;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class NeoBlizzy
{
    public function make(string $region = 'eu')
    {
        return new BNetApiHelper($region);
    }

    public function cacheApiCall($url, $parameters)
    {
        $httpTimeout = 20;

        if (config('neoblizzy.enable_cache', true)) {
            return Cache::remember('neoblizzy.' . md5($url), config('neoblizzy.cache_timeout'), function () use($url, $parameters, $httpTimeout) {
                return Http::timeout($httpTimeout)->get($url, $parameters)->object();
            });
        }

        return Http::timeout($httpTimeout)->get($url, $parameters)->object();
    }
}
