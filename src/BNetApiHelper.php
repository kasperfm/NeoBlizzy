<?php

namespace KasperFM\NeoBlizzy;

use Illuminate\Support\Facades\Http;
use KasperFM\NeoBlizzy\Services\D3Service;
use KasperFM\NeoBlizzy\Services\WoWService;

class BNetApiHelper
{
    protected string $baseDomain = 'battle.net';
    protected string $region = 'eu';
    protected string $domain;
    protected string $accessToken;

    public function __construct(string $region = 'eu')
    {
        $this->setRegion($region);
    }

    public function setRegion(string $region)
    {
        $this->region = $region;

        return $this;
    }

    public function createAccessToken($apiKey = null, $apiSecret = null) {
        if (!$apiKey) {
            $apiKey = config('neoblizzy.api_key');
        }

        if (!$apiSecret) {
            $apiSecret = config('neoblizzy.secret_key');
        }

        if (empty($apiKey) || empty($apiSecret)) {
            throw new \Exception('Battle.net API keys is empty. Please fill out the env variables.');
        }

        $httpResponse = Http::asMultipart()
            ->withBasicAuth($apiKey, $apiSecret)
            ->post('https://' . $this->region . '.' . $this->baseDomain . '/oauth/token', ['grant_type' => 'client_credentials']);

        if (!property_exists($httpResponse->object(), 'access_token')) {
            throw new \Exception('Unable to retrieve access token from Blizzard!');
        }

        $this->accessToken = $httpResponse->object()->access_token;

        return $this->accessToken;
    }

    public function wowApi()
    {
        if (empty($this->accessToken)) {
            $this->createAccessToken();
        }

        return new WoWService($this->accessToken, $this->region);
    }

    public function diablo3Api()
    {
        if (empty($this->accessToken)) {
            $this->createAccessToken();
        }

        return new D3Service($this->accessToken, $this->region);
    }
}
