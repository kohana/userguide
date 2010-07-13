# Berichten (Messages): de basis

Kohana berichten zijn mensvriendelijke stukjes tekst voorgesteld door een korter woord of zin, een "key" genaamd. Berichten worden benaderd via de [Kohana::message] methode, die één enkel of een hele groep van berichten teruggeeft.

Bijvoorbeeld, als een gebruiker niet is ingelogd en een pagina dat authenticatie vereist probeert te benaderen, dan moet een error zoals "U moet ingelogd zijn om toegang te hebben tot deze pagina" getoond worden. Dit bericht kan opgeslagen worden in het `auth` bestand met een `must_login` key:

    $message = Kohana::message('auth', 'must_login');

Berichten worden niet vertaald. Om een bericht te vertalen, gebruik dan de [translation function](using.translation):

    $translated = __(Kohana::message('auth', 'must_login'));

[!!] In Kohana v2 werd het berichten-systeem gebruikt voor vertalingen. Echter is het ten zeerste aanbevolen om het nieuwe vertalingssysteem te gebruiken in plaats van berichten, aangezien het leesbare tekst teruggeeft wanneer zelfs geen vertaling beschikbaar is.

## Berichten: de bestanden

Alle berichten bestanden zijn pure PHP files, opgeslaan in de `messages/` folder, die een associatieve array teruggeven:

    <?php defined('SYSPATH') or die('No direct script access.');

    return array(
        'must_login' => 'U moet ingelogd zijn om toegang te hebben tot deze pagina',
        'no_access'  => 'U heeft geen bevoegdheden om deze pagina te bekijken',
    );

Berichten bestanden zijn gelijkaardig aan [configuratie bestanden](using.configuration#config-files) omdat ze ook worden samengevoegd. Dit betekent dat alle berichten die opgeslaan zijn in het bestand `auth` zullen worden gecombineerd in één enkele array, het is dus niet noodzakelijk om alle berichten te kopiëren wanneer je een nieuw `auth` bestand aanmaakt.
