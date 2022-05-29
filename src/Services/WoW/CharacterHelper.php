<?php

namespace KasperFM\NeoBlizzy\Services\WoW;

use KasperFM\NeoBlizzy\Services\ApiResult;

class CharacterHelper
{
    protected string $name;
    protected string $realm;
    protected string $gender;
    protected int $level;
    protected int $exp;
    protected int $achievementPoints;
    protected string $faction;
    protected string $race;
    protected string $class;
    protected string $currentSpec;

    public function __construct(ApiResult $apiResult)
    {
        $this->setProperty('name', $apiResult->name);
        $this->setProperty('realm', $apiResult->realm->name);
        $this->setProperty('gender', $apiResult->gender->name);
        $this->setProperty('level', $apiResult->level);
        $this->setProperty('exp', $apiResult->experience);
        $this->setProperty('achievementPoints', $apiResult->achievement_points);
        $this->setProperty('faction', $apiResult->faction->name);
        $this->setProperty('race', $apiResult->race->name);
        $this->setProperty('class', $apiResult->character_class->name);
        $this->setProperty('currentSpec', $apiResult->active_spec->name);
    }

    private function setProperty($propName, $value)
    {
        if (property_exists($this, $propName)) {
            $this->{$propName} = $value;
        }
    }
}
