<?php

namespace KasperFM\NeoBlizzy\OAuth2\Providers;

use KasperFM\NeoBlizzy\NeoBlizzyFacade as NeoBlizzy;
use KasperFM\NeoBlizzy\OAuth2\Entity\WoWUser;
use League\OAuth2\Client\Token\AccessToken;

class WoWProvider extends BattleNet
{
    protected $game = "wow";
    protected $region = "eu";

    public function getResourceOwnerDetailsUrl(AccessToken $token)
    {
        return "https://{$this->region}.api.blizzard.com/profile/user/wow?namespace=profile-{$this->region}&access_token={$token}";
    }

    protected function createResourceOwner(array $response, AccessToken $token)
    {
        if (empty($response)) {
            abort(404);
        }

        return new WoWUser($response, $this->region);
    }


}
