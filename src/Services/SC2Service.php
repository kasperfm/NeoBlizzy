<?php

namespace KasperFM\NeoBlizzy\Services;

use Illuminate\Support\Str;
use KasperFM\NeoBlizzy\Models\NeoBlizzyOAuth2Token;
use KasperFM\NeoBlizzy\NeoBlizzyFacade as NeoBlizzy;
use KasperFM\NeoBlizzy\OAuth2\Entity\SC2User;
use KasperFM\NeoBlizzy\OAuth2\Providers\SC2Provider;
use League\OAuth2\Client\Token\AccessToken;

class SC2Service extends BaseService
{
    protected string $gameParameter = 'sc2';
    protected string $token;
    protected string $region;

    public function authWithApi()
    {
        $provider = new SC2Provider([
            'clientId' => config('neoblizzy.api_key'),
            'clientSecret' => config('neoblizzy.secret_key'),
            'redirectUri' => route('neoblizzy.sc2auth')
        ]);

        if (!isset($_GET['code'])) {
            $authorizationUrl = $provider->getAuthorizationUrl();

            // Get the state generated for you and store it to the session.
            $_SESSION['oauth2state'] = $provider->getState();

            return response()->redirectTo($authorizationUrl);
        }

        $accessToken = $provider->getAccessToken('authorization_code', [
            'code' => $_GET['code']
        ]);

        $apiValues = $provider->getResourceOwner($accessToken)->toArray();

        NeoBlizzyOAuth2Token::firstOrCreate(
            ['token' => $accessToken->getToken(), 'user_id' => 1],
            ['game' => 'sc2', 'region' => $apiValues['region'], 'realm' => $apiValues['realm'], 'profile_id' => $apiValues['id']]
        );

        $this->setToken($accessToken->getToken());

        return response()->redirectTo(route('neoblizzy.sc2redirect', ['profile' => $apiValues['id']]));
    }

    public function setToken($token)
    {
        $this->token = $token;

        return $this;
    }

    public function getTokenData($profileID)
    {
        return NeoBlizzyOAuth2Token::where('profile_id', $profileID)->firstOrFail();
    }

    public function getProfile($profileId, $realmId, $regionId, $token)
    {
        $playerDataResponse = NeoBlizzy::cacheApiCall("https://{$this->region}.api.blizzard.com/sc2/profile/{$regionId}/{$realmId}/{$profileId}?locale=en_GB", null, $token);

        return new SC2User($playerDataResponse, $this->region);
    }

    public function getAllLadders($profileId, $realmId, $regionId, $token)
    {
        return NeoBlizzy::cacheApiCall("https://{$this->region}.api.blizzard.com/sc2/profile/{$regionId}/{$realmId}/{$profileId}/ladder/summary?locale=en_GB", null, $token);
    }

    public function getLadder($profileId, $realmId, $regionId, $ladderId, $token)
    {
        return NeoBlizzy::cacheApiCall("https://{$this->region}.api.blizzard.com/sc2/profile/{$regionId}/{$realmId}/{$profileId}/ladder/{$ladderId}?locale=en_GB", null, $token);
    }

    public function getMmrRatings($profileId, $realmId, $regionId, $token)
    {
        $ladders = $this->getAllLadders($profileId, $realmId, $regionId, $token);

        if (!is_object($ladders)) {
            return null;
        }

        $result = [];

        foreach($ladders->allLadderMemberships as $ladder) {
            if (!is_object($ladder)) {
                continue;
            }

            $ladderInfo = $this->getLadder($profileId, $realmId, $regionId, $ladder->ladderId, $token);

            $userLadderData = collect($ladderInfo->ladderTeams)->filter(function ($value) use ($profileId) {
                return collect($value->teamMembers)->flatten()->contains('id', $profileId);
            })->first();

            $result[] = [
                'ladderId' => $ladder->ladderId,
                'rank' => $ladder->rank,
                'gameMode' => $ladder->localizedGameMode,
                'favoriteRace' => $userLadderData->teamMembers[0]->favoriteRace,
                'mmr' => $userLadderData->mmr,
                'wins' => $userLadderData->wins,
                'losses' => $userLadderData->losses
            ];
        }

        return $result;
    }
}
