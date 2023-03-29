<?php

namespace KasperFM\NeoBlizzy\OAuth2\Providers;

use KasperFM\NeoBlizzy\NeoBlizzyFacade as NeoBlizzy;
use KasperFM\NeoBlizzy\OAuth2\Entity\SC2User;
use League\OAuth2\Client\Token\AccessToken;

class SC2Provider extends BattleNet
{
    protected $game = "sc2";
    protected $region = "eu";

    public function getResourceOwnerDetailsUrl(AccessToken $token)
    {
        return "https://{$this->region}.api.blizzard.com/sc2/player/{$token->getValues()['sub']}?access_token={$token}";
    }

    protected function createResourceOwner(array $response, AccessToken $token)
    {
        if (empty($response)) {
            abort(404);
        }

        $playerDataResponse = NeoBlizzy::cacheApiCall("https://{$this->region}.api.blizzard.com/sc2/profile/{$response[0]['regionId']}/{$response[0]['realmId']}/{$response[0]['profileId']}?locale=en_GB", null, $token->getToken());

        return new SC2User($playerDataResponse, $this->region);
    }


}
