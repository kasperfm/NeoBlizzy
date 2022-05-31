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
    protected string $avatarUrl;
    protected string $characterDisplayUrl;

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

        foreach ($apiResult->media->assets as $asset) {
            if ($asset->key == 'avatar') {
                $this->setProperty('avatarUrl', $asset->value);
            }

            if ($asset->key == 'main') {
                $this->setProperty('characterDisplayUrl', $asset->value);
            }
        }
    }

    private function setProperty($propName, $value)
    {
        if (property_exists($this, $propName)) {
            $this->{$propName} = $value;
        }
    }

    public function getAchievementPoints(): int
    {
        return $this->achievementPoints;
    }

    public function getAvatarUrl(): string
    {
        return $this->avatarUrl;
    }

    public function getCharacterDisplayUrl(): string
    {
        return $this->characterDisplayUrl;
    }

    public function getClass(): string
    {
        return $this->class;
    }

    public function getCurrentSpec(): string
    {
        return $this->currentSpec;
    }

    public function getExp(): int
    {
        return $this->exp;
    }

    public function getFaction(): string
    {
        return $this->faction;
    }

    public function getGender(): string
    {
        return $this->gender;
    }

    public function getLevel(): int
    {
        return $this->level;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getRace(): string
    {
        return $this->race;
    }

    public function getRealm(): string
    {
        return $this->realm;
    }

}
