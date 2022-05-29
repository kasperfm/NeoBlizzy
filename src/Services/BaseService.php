<?php

namespace KasperFM\NeoBlizzy\Services;

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

    protected function callGetApi(string $endpoint, array $parameters)
    {
        $parameters = array_merge($parameters, [
            'access_token' => $this->accessToken,
            'locale' => config('neoblizzy.locale')
        ]);

        $apiResponse = collect(\NeoBlizzy::cacheApiCall('https://' . $this->region . '.' . $this->baseDomain . '/' . $endpoint, $parameters));
        $return = $apiResponse->pipeInto(ApiResult::class);

        return $return->setAccessToken($this->accessToken)->followLinks();
    }
}
