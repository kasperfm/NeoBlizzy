# NeoBlizzy

This is a Laravel (v8.x and 9.x) package for interacting with the [Battle.net API](https://develop.battle.net/) from Blizzard Entertainment.

It's still in a very early and simple stage, and can only handle a few World of Warcraft and Diablo 3 endpoints so far.

## How to use
1) Install the package with composer:
   ```bash
   composer require kasperfm/neoblizzy
   ```
   
   And run migrations using:
   ```bash
   php artisan migrate
   ```


2) Setup your Battle.net API keys and locale in your .env file, like this example:
   ```php
   BATTLENET_API_KEY=abc123
   BATTLENET_API_SECRET=secret321
   BATTLENET_LOCALE=en_GB
   ```
   If you don't have any keys you can create them [here](https://develop.battle.net/access/clients).


3) If you want to enable or disable Laravel's cache system for caching the results from the API, you can set it in the .env file too. It's enabled by default, for 120 seconds.
   ```php
   BATTLENET_ENABLE_CACHE=true
   BATTLENET_CACHE_TIMEOUT=120
   ```
   
4) If you want to use the Starcraft 2 API you need to set the env variable `BATTLENET_SC2_REDIRECT_URI` too. This is the URL that the user will be redirected to after a user authenticate with Starcraft 2 and the Battle.net API. The URL will have the query parameter `profile=123456` containing the players SC2 profile ID, for future use.
   

5) Yay! Setup is now done, and you can now use the package in your application.

----
### World of Warcraft example
   Here is an example where ``Zomixiana`` is my character name, and ``Tarren Mill`` is the server/realm in this case.
   ```php
   $wowChar = \NeoBlizzy::make()->setRegion('eu')->wowApi()->getCharacter('Zomixiana', 'Tarren Mill');
   ``` 

   ```php
   dd($wowChar);
   
   // The result of the dd($wowChar) function.
   KasperFM\NeoBlizzy\Services\WoW\CharacterHelper {
     +name: "Zomixiana"
     +realm: "Tarren Mill"
     +gender: "Female"
     +level: 56
     +exp: 78491
     +achievementPoints: 4375
     +faction: "Horde"
     +race: "Undead"
     +class: "Mage"
     +currentSpec: "Frost"
     +avatarUrl: "https://render.worldofwarcraft.com/eu/character/tarren-mill/122/174314618-avatar.jpg"
     +characterDisplayUrl: "https://render.worldofwarcraft.com/eu/character/tarren-mill/122/174314618-main.jpg"
   }


   dd($wowChar->getName());
   // The result of the getName() function on the $wowChar object.
   "Zomixiana"
   ```

### Starcraft 2 example
An example getting your basic Starcraft 2 profile:

```php
$tokenData = NeoBlizzy::make()->setRegion('eu')->sc2Api()->getTokenData($request->get('profile'));

dd(NeoBlizzy::make()->setRegion('eu')->sc2Api()->getProfile($tokenData->profile_id, $tokenData->realm, $tokenData->region, $tokenData->token));
```

```php
// The result of the dd(NeoBlizzy::make()->...sc2Api()->getProfile($tokenData.....) function.
KasperFM\NeoBlizzy\OAuth2\Entity\SC2User {
  +id: "557573"
  +realm: 1
  +region_id: 2
  +name: "Neo"
  +profile_url: "https://starcraft2.com/profile/2/1/557573"
  +league: null
  +terran_wins: 0
  +protoss_wins: 0
  +zerg_wins: 0
  +season_total_games: 3
  +highest_league: "GOLD"
  +career_total_games: 239
  +total_achievement_points: 2325
  +portrait_url: "https://static.starcraft2.com/starport/bda9a860-ca36-11ec-b5ea-4bed4e205979/portraits/1-30.jpg"
}
```
This code can be used in the controller method where ``BATTLENET_SC2_REDIRECT_URI`` redirects to.
