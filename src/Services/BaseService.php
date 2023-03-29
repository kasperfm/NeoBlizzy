<?php

namespace KasperFM\NeoBlizzy\Services;

use Illuminate\Support\Facades\Http;
use KasperFM\NeoBlizzy\NeoBlizzyFacade as NeoBlizzy;
use KasperFM\NeoBlizzy\OAuth2\Providers\SC2Provider;

class BaseService
{
    protected string $baseDomain = 'api.blizzard.com';
    protected string $accessToken= 'no_access_token_defined';

    protected string $gameParameter;
    protected string $region;

    public function __construct(string $accessToken, string $region = 'eu')
    {
        $this->accessToken = $accessToken;
        $this->setRegion($region);
    }

    public function setRegion(string $region)
    {
        $this->region = $region;

        return $this;
    }

    protected function callGetApi(string $endpoint, array $parameters = [])
    {
        $parameters = array_merge($parameters, [
            'locale' => config('neoblizzy.locale')
        ]);

        $apiResponse = collect(NeoBlizzy::cacheApiCall('https://' . $this->region . '.' . $this->baseDomain . '/' . $endpoint, $parameters, $this->accessToken));
        if ($apiResponse->has('code') && $apiResponse->has('detail') && $apiResponse->get('code') != 200) {
            throw new \Exception('API Error ' . $apiResponse->get('code') . ': ' . $apiResponse->get('detail'));
        }

        $return = $apiResponse->pipeInto(ApiResult::class);

        return $return->setAccessToken($this->accessToken)->followLinks();
    }
}
