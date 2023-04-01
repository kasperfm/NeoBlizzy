<?php

namespace KasperFM\NeoBlizzy\OAuth2\Entity;

use KasperFM\NeoBlizzy\OAuth2\Providers\WoWProvider;
use League\OAuth2\Client\Provider\ResourceOwnerInterface;

class WoWUser implements ResourceOwnerInterface
{
    public $id;
    public $region_id;
    public $characters = [];

    public function __construct($attributes, string $region)
    {
        $this->region_id = WoWProvider::$regionIdList[$region];
        $this->id = $attributes['id'];

        $wowAccount = $attributes['wow_accounts'][0];

        if (isset($wowAccount['characters'])) {
            foreach ($wowAccount['characters'] as $character) {
                $characterEntry = [];

                $characterEntry['id'] = $character['id'];
                $characterEntry['name'] = $character['name'];
                $characterEntry['level'] = $character['level'];
                $characterEntry['profile_url'] = $character['character']['href'];
                $characterEntry['realm']['name'] = $character['realm']['name'][config('neoblizzy.locale')];
                $characterEntry['realm']['slug'] = $character['realm']['slug'];
                $characterEntry['realm']['id'] = $character['realm']['id'];
                $characterEntry['realm']['data_url'] = $character['realm']['key']['href'];
                $characterEntry['gender'] = $character['gender']['name'][config('neoblizzy.locale')];
                $characterEntry['class'] = $character['playable_class']['name'][config('neoblizzy.locale')];
                $characterEntry['race'] = $character['playable_race']['name'][config('neoblizzy.locale')];
                $characterEntry['faction'] = $character['faction']['name'][config('neoblizzy.locale')];

                $this->characters[] = $characterEntry;
            }
        }
    }

    public function toArray()
    {
        return [
            'id' => $this->id,
            'region' => $this->region_id,
            'characters' => $this->characters
        ];
    }

    public function getId()
    {
        return $this->id;
    }
}
