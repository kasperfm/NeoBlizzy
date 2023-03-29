<?php

namespace KasperFM\NeoBlizzy\Services;

use Illuminate\Support\Str;
use KasperFM\NeoBlizzy\Services\WoW\CharacterHelper;

class WoWService extends BaseService
{
    protected string $gameParameter = 'wow';

    public function getCharacterRaw(string $name, string $realm)
    {
        return $this->callGetApi('profile/'.$this->gameParameter.'/character/'.Str::slug($realm).'/'.Str::slug($name), [
            'namespace' => 'profile-' . $this->region
        ]);
    }

    public function getCharacter(string $name, string $realm)
    {
        return new CharacterHelper($this->getCharacterRaw($name, $realm));
    }
}
