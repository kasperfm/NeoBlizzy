<?php
namespace KasperFM\NeoBlizzy;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class NeoBlizzy
{
    public function make(string $region = 'eu') : BNetApiHelper
    {
        return new BNetApiHelper($region);
    }

    public static function cacheApiCall(string $url, array|null $parameters, string $token = null)
    {
        $httpTimeout = 30;

        if (config('neoblizzy.enable_cache', true)) {
            return Cache::remember('neoblizzy.' . md5($url), config('neoblizzy.cache_timeout'), function () use($url, $parameters, $httpTimeout, $token) {
                if ($token) {
                    return Http::timeout($httpTimeout)->withToken($token)->get($url, $parameters)->object();
                }

                return Http::timeout($httpTimeout)->get($url, $parameters)->object();
            });
        }

        if ($token) {
            return Http::timeout($httpTimeout)->withToken($token)->get($url, $parameters)->object();
        }

        return Http::timeout($httpTimeout)->get($url, $parameters)->object();
    }
}
