<?php

namespace KasperFM\NeoBlizzy\Services;

use Illuminate\Support\Facades\Http;

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

    protected function callGetApi(string $endpoint, array $parameters): object|array
    {
        $parameters = array_merge($parameters, [
            'access_token' => $this->accessToken
        ]);

        return Http::get('https://' . $this->region . '.' . $this->baseDomain . '/' . $endpoint, $parameters)->object();
    }
}
