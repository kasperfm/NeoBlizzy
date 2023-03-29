<?php

namespace KasperFM\NeoBlizzy\OAuth2\Providers;

use League\OAuth2\Client\Provider\AbstractProvider;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use League\OAuth2\Client\Token\AccessToken;
use Psr\Http\Message\ResponseInterface;

abstract class BattleNet extends AbstractProvider
{

    /**
     * The game we wish to query. Defaults to SC2. Available options are:
     *  * sc2
     *  * wow
     * @var string
     */
    protected $game;

    /**
     * The Battle.net region we wish to query on. Available options are:
     *  * us
     *  * eu
     *  * kr
     *  * tw
     *  * cn
     *  * sea (sc2-only!)
     *
     * @var string
     */
    protected $region = "eu";

    protected $regionId = 2;

    // The region for the profile (1=US, 2=EU, 3=KO and TW, 5=CN).
    static public $regionIdList = [
        'us' => 1,
        'eu' => 2,
        'ko' => 3,
        'tw' => 3,
        'cn' => 5
    ];

    const ACCESS_TOKEN_RESOURCE_OWNER_ID = 'accountId';

    public function __construct(array $options = [ ], array $collaborators = [ ])
    {
        parent::__construct($options, $collaborators);

        // We need to validate some data to make sure we haven't constructed in an illegal state.
        if (!in_array($this->game, [ "sc2", "wow"])) {
            throw new \InvalidArgumentException("Game must be either sc2 or wow, given: {$this->game}");
        }

        $availableRegions = [ "us", "eu", "kr", "tw", "cn"];
        if (!in_array($this->region, $availableRegions)) {
            $regionList = implode(", ", $availableRegions);
            throw new \InvalidArgumentException("Region must be one of: {$regionList}, given: {$this->region}");
        }

        $this->regionId = self::$regionIdList[$this->region];
    }


    protected function getScopeSeparator()
    {
        return " ";
    }

    public function getBaseAuthorizationUrl()
    {
        return "https://oauth.battle.net/authorize";
    }

    public function getBaseAccessTokenUrl(array $params)
    {
        return "https://oauth.battle.net/token";
    }

    protected function getDefaultScopes()
    {
        return [
            "{$this->game}.profile"
        ];
    }

    protected function checkResponse(ResponseInterface $response, $data)
    {
        if ($response->getStatusCode() != 200) {
            $data = (is_array($data)) ? $data : json_decode($data, true);
            throw new IdentityProviderException($data['error_description'], $response->getStatusCode(), $data);
        }
    }
}
