<?php

namespace KasperFM\NeoBlizzy\OAuth2\Entity;

use KasperFM\NeoBlizzy\OAuth2\Providers\SC2Provider;
use League\OAuth2\Client\Provider\ResourceOwnerInterface;

class SC2User implements ResourceOwnerInterface
{
    public $id;
    public $realm;
    public $region_id;
    public $name;
    public $profile_url;
    public $league;
    public $terran_wins;
    public $protoss_wins;
    public $zerg_wins;
    public $season_total_games;
    public $highest_league;
    public $career_total_games;
    public $total_achievement_points;
    public $portrait_url;

    public function __construct($attributes, string $region)
    {
        $this->region_id = SC2Provider::$regionIdList[$region];

        $this->id = $attributes->summary->id;
        $this->realm = $attributes->summary->realm;
        $this->name = $attributes->summary->displayName;
        $this->profile_url = "https://starcraft2.com/profile/{$this->region_id}/{$this->realm}/{$this->id}";
        $this->total_achievement_points = $attributes->summary->totalAchievementPoints;

        if (isset($attributes->summary->portrait)) {
            $this->portrait_url = $attributes->summary->portrait;
        }

        if (isset($attributes->career)) {
            $career = (is_object($attributes->career)) ? (array)$attributes->career : $attributes->career;
            $this->league = (isset($career['current1v1LeagueName'])) ? $career['current1v1LeagueName'] : null;
            $this->terran_wins = (isset($career['terranWins'])) ? $career['terranWins'] : null;
            $this->protoss_wins = (isset($career['protossWins'])) ? $career['protossWins'] : null;
            $this->zerg_wins = (isset($career['zergWins'])) ? $career['zergWins'] : null;
            $this->season_total_games = (isset($career['totalGamesThisSeason'])) ? $career['totalGamesThisSeason'] : null;
            $this->career_total_games = (isset($career['totalCareerGames'])) ? $career['totalCareerGames'] : null;
            $this->highest_league = (isset($career['best1v1Finish']->leagueName)) ? $career['best1v1Finish']->leagueName : null;
        }
    }

    public function toArray()
    {
        return [
            'id' => $this->id,
            'realm' => $this->realm,
            'region' => $this->region_id,
            'name' => $this->name,
            'profile_url' => $this->profile_url,
            'achievement_points' => $this->total_achievement_points
        ];
    }

    public function getId()
    {
        return $this->id;
    }
}
