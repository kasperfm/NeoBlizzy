<?php

namespace KasperFM\NeoBlizzy\Services;

use Illuminate\Support\Str;

class D3Service extends BaseService
{
    protected string $gameParameter = 'd3';

    public function getProfile(string $battletag)
    {
        return $this->callGetApi($this->gameParameter.'/profile/'.urlencode($battletag).'/');
    }

    public function getHero(string $battletag, int $heroId)
    {
        return $this->callGetApi($this->gameParameter.'/profile/'.urlencode($battletag).'/hero/'.$heroId);
    }
}
