<?php

namespace KasperFM\NeoBlizzy\Services;

use Illuminate\Support\Str;
use KasperFM\NeoBlizzy\Services\WoW\CharacterHelper;

class WoWService extends BaseService
{
    protected string $gameParameter = 'wow';

    public function getCharacter(string $name, string $realm)
    {
        return $this->callGetApi('profile/'.$this->gameParameter.'/character/'.Str::slug($realm).'/'.Str::slug($name), [
            'namespace' => 'profile-' . $this->region
        ]);
    }

    public function getCharacterData(string $name, string $realm)
    {
        $character = $this->callGetApi('profile/'.$this->gameParameter.'/character/'.Str::slug($realm).'/'.Str::slug($name), [
            'namespace' => 'profile-' . $this->region
        ]);

        return new CharacterHelper($character);
    }
}
