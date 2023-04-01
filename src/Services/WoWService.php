<?php

namespace KasperFM\NeoBlizzy\Services;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use KasperFM\NeoBlizzy\Models\NeoBlizzyOAuth2Token;
use KasperFM\NeoBlizzy\OAuth2\Entity\WoWUser;
use KasperFM\NeoBlizzy\OAuth2\Providers\WoWProvider;
use KasperFM\NeoBlizzy\Services\WoW\CharacterHelper;

class WoWService extends BaseService
{
    protected string $gameParameter = 'wow';

    public function getAllCharacters($accountId)
    {
        $characters = collect(Cache::get('neoblizzy.WoWCharacters.'.$accountId));
        return $characters;
    }

    public function getCharacter(int $accountId, string $name, string $realm)
    {
        $characters = $this->getAllCharacters($accountId);
        return $characters->where('name', $name)->where('realm.name', $realm)->firstOrFail();
    }

    public function clearCache()
    {
        return Cache::tags(['neoblizzy.wowcharacters'])->flush();
    }

    public function authWithApi()
    {
        $provider = new WoWProvider([
            'clientId' => config('neoblizzy.api_key'),
            'clientSecret' => config('neoblizzy.secret_key'),
            'redirectUri' => route('neoblizzy.wowauth')
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

        NeoBlizzyOAuth2Token::updateOrCreate(
            [
                'token' => $accessToken->getToken(),
                'game' => $this->gameParameter
            ],
            [
                'region' => $apiValues['region'],
                'realm' => null,
                'profile_id' => $apiValues['id'],
                'user_id' => auth()?->id() ?? null
            ]
        );

        $this->setToken($accessToken->getToken());

        Cache::tags(['neoblizzy.wowcharacters'])->set('neoblizzy.WoWCharacters.'.$apiValues['id'], $apiValues['characters'] ?? []);

        return response()->redirectTo(route('neoblizzy.wowredirect', ['profile' => $apiValues['id']]));
    }
}
