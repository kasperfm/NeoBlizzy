<?php

namespace KasperFM\NeoBlizzy\Services;

class WoWService extends BaseService
{
    protected string $gameParameter = 'wow';

    public function getCharacter(string $name, string $realm)
    {
        return $this->callGetApi('profile/'.$this->gameParameter.'/character/'.$realm.'/'.$name, [
            'namespace' => 'profile-' . $this->region,
            'locale' => config('neoblizzy.locale')
        ]);
    }
}
