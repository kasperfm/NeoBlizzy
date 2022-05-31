# NeoBlizzy

This is a Laravel (v8.x and 9.x) package for interacting with the [Battle.net API](https://develop.battle.net/) from Blizzard Entertainment.

It's still in a very early and simple stage, and can only handle a few World of Warcraft and Diablo 3 endpoints so far.

## How to use
1) Install the package with composer:
   ```bash
   composer require kasperfm/neoblizzy
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

4) Yay! Setup is now done, and you can now use the package in your application.
   Here is an example where ``Zomixiana`` is my character name, and ``Tarren Mill`` is the server/realm in this case.
   ```php
   $wowChar = \NeoBlizzy::make()->setRegion('eu')->wowApi()->getCharacterData('Zomixiana', 'Tarren Mill');
   ``` 

   ```php
   dd($wowChar);
   
   // The result of the dd($wowChar) function.
   KasperFM\NeoBlizzy\Services\WoW\CharacterHelper {
     #name: "Zomixiana"
     #realm: "Tarren Mill"
     #gender: "Female"
     #level: 56
     #exp: 78491
     #achievementPoints: 4375
     #faction: "Horde"
     #race: "Undead"
     #class: "Mage"
     #currentSpec: "Frost"
     #avatarUrl: "https://render.worldofwarcraft.com/eu/character/tarren-mill/122/174314618-avatar.jpg"
     #characterDisplayUrl: "https://render.worldofwarcraft.com/eu/character/tarren-mill/122/174314618-main.jpg" }


   dd($wowChar->getName());
   
   // The result of the getName() function on the $output object.
   "Zomixiana"

   ```
